<?php

namespace App\Filament\Pages;

use App\Models\Diligenciamiento;
use App\Models\Configuration;
use App\Models\Logo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Support\Htmlable;

use Filament\Pages\Page;
use App\Exports\DiligenciamientoExport;
use Maatwebsite\Excel\Facades\Excel;
class Filtros extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.filters';

    public $filterValues = [];

    public $selectedColums = [];

    public $conditions = [];

    public $inputValue = '';

    public $condition = '';

    public $variable;

    public $diligenciamientos;

    public $configurations;

    public $appGeoValues;

    public $choosedFilter = false;

    public $openAlert = false;

    public $noFilterApplied = false;

    public $selectedOption = '';

    public $selectedValue;

    public $filteredOptions = '';

    public $showGraphics = false;

    public $loadingPdf = false;

    public function getTitle(): string|Htmlable
    {
        return __('Filtros');
    }

    public static function shouldRegisterNavigation(): bool
    {
        $configurations = Configuration::get();

        return $configurations->count() > 0;
    }

    public function mount()
    {

        $this->configurations = Configuration::query()->get();

        $this->openAlert = true;
        $this->loadingPdf = true;
    }

    public function choosedFilterFunction()
    {
        $this->choosedFilter = true;
    }

    public function resetValues()
    {
        $this->selectedOption = '';
        $this->inputValue = '';
        $this->condition = '';
        $this->choosedFilter = false;
        $this->showGraphics = false;
    }

    public function resetFilter()
    {

        if (in_array($this->selectedOption, $this->selectedColums)) {
            $this->openAlert = true;
        } else {
            $this->selectedColums[] = $this->selectedOption;
            $this->filterValues[] = $this->inputValue;
            $this->conditions[] = $this->condition;
        }

        $this->resetValues();
    }

    public function resetFilterData()
    {
        $this->selectedColums = [];
        $this->filterValues = [];
        $this->conditions = [];
        $this->diligenciamientos = null;
        $this->resetValues();
    }

    public function removeFilter($index)
    {
        $this->showGraphics = false;
        unset($this->selectedColums[$index]);
        unset($this->filterValues[$index]);
        unset($this->conditions[$index]);

        // Re-index arrays to prevent gaps in the indices
        $this->selectedColums = array_values($this->selectedColums);
        $this->filterValues = array_values($this->filterValues);
        if ($this->selectedColums === []) {
            $this->diligenciamientos = null;
        } else {
            $this->getFilteredData();
        }
    }

    public function generateGraphics()
    {
        $this->showGraphics = true;
    }

    public function getFilteredData()
    {

        $query = Diligenciamiento::query();


        if (count($this->selectedColums) === 0) {

            $this->noFilterApplied = true;
            $this->openAlert = true;
        } else {
            if (count($this->selectedColums) === count($this->filterValues)) {

                foreach ($this->selectedColums as $index => $selectedColum) {

                    if ($selectedColum === 'edad') {
                        $filterValue = $this->filterValues[$index];

                        $condition = $this->conditions[$index];

                        $query->where($selectedColum, $condition, $filterValue);
                    } else {
                        $filterValue = $this->filterValues[$index];

                        $query->where($selectedColum, '=', $filterValue);
                    }
                }
                $this->diligenciamientos = $query->get();
            }
        }
    }

    public function closeAlert()
    {
        $this->noFilterApplied = false;
        $this->openAlert = false;
        $this->loadingPdf = false;
    }

    public function generarExcel()
    {
        $diligenciamientos = $this->diligenciamientos; // O filtra según sea necesario
        return Excel::download(new DiligenciamientoExport($diligenciamientos), 'diligenciamientos.xlsx');
    }
}

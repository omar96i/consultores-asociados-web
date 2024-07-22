<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use App\Models\Configuration;

class Configuracion extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.Configuracion';

    public $configurations;
    public $departamento = '';
    public $municipio = '';
    public $activateAlcaldiaLogo = false;
    public $activateDepartamentoLogo = false;
    public $isEntriesComplete;
    public $alert = false;

    protected $rules = [
        'departamento' => 'required|string|max:255',
        'municipio' => 'required|string|max:255',
        'activateAlcaldiaLogo' => 'boolean',
        'activateDepartamentoLogo' => 'boolean',
    ];

    public function getTitle(): string | Htmlable
{
    return __('Configuracion de la aplicacion');
}

public function mount(): void
{
    $this->configurations = Configuration::query()->get();
}


public function saveConfiguration()
    {
        $isComplete = $this->departamento !== null && $this->municipio !== null;
        

        if($this->departamento === '')
        {
            $this->alert = true;
        }
        else
        {
        // Save to database
        // Assuming you have a Configuration model and table
        \App\Models\Configuration::create([
            'departamento' => $this->departamento,
            'alcaldia' => $this->municipio,
            'alcaldia_logo_active' => $this->activateAlcaldiaLogo,
            'departamento_logo_active' => $this->activateDepartamentoLogo,
            'status_complete' => $isComplete,
        ]);
        }



        if($isComplete === true){
            $this->configurations = Configuration::query()->get();
        }


        // Optionally, add a session flash message or any other feedback
        session()->flash('message', 'Configuración guardada correctamente.');
    }

    public function resetValues(){
        Configuration::query()->delete();
        $this->mount();
        return redirect()->back()->with('success', 'Todos los valores han sido borrados.');
    }

    public function closeAlert() {
        $this->alert = false;
    }
}

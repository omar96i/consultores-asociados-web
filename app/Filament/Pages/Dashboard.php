<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use App\Models\Logo;
use App\Models\Configuration;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dashboard';

    public $consultoresLogo = "storage/images/consultores_icon.jpg";

    public $mapPath = "storage/images/departamento_map.jpg";

    public $alcaldiaPath = "storage/images/alcaldia_icon.jpg";

    public $mapExist;

    public $configurations;

    public $alcaldiaLogoExist;

    public $logos;
    

    public function getTitle(): string | Htmlable
{
    return __('');
}



public function mount(){

    $this->configurations = Configuration::query()->get();

    $mapPath = "storage/images/departamento_map.jpg";
    $alcaldiaPath = "storage/images/alcaldia_icon.jpg";

    if(file_exists(public_path($mapPath)) === true)
    {
        $this->mapExist = true;
    }else
    {
        $this->mapExist = false;
    }

    if(file_exists(public_path($alcaldiaPath)) === true)
    {
        $this->alcaldiaLogoExist = true;
    }else
    {
        $this->alcaldiaLogoExist = false;
    }



}




}

<?php

namespace MicroweberPackages\Modules\GoogleMaps\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class GoogleMapsSettingsComponent extends ModuleSettingsComponent
{

    public array $settings = [
        'data-address' => '',
        'data-zoom' => '',

    ];

    public function render()
    {

        return view('microweber-module-google-maps::livewire.index');
    }

}

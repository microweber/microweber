<?php

namespace MicroweberPackages\Modules\GoogleMaps\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class GoogleMapsSettingsComponent extends ModuleSettingsComponent
{

    public array $settings = [
        'data-address' => '',
        'data-zoom' => '',

    ];

    public array $settingsForm = [
        'data-address' => [
            'type' => 'text',
            'label' => 'Address',
            'help' => 'Enter address',
            'placeholder' => 'Enter address',
        ],
        'data-zoom' => [
            'type' => 'slider',
            'label' => 'Zoom',
            'help' => 'Enter zoom',
            'min' => '1',
            'max' => '10',
            'placeholder' => 'Enter zoom',
        ],
    ];


    public function render()
    {

        return view('microweber-module-google-maps::livewire.index');
    }

}

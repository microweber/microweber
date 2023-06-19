<?php

namespace MicroweberPackages\Modules\GoogleMaps\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsFormComponent;

class GoogleMapsSettingsComponent extends ModuleSettingsFormComponent
{

    public $moduleTitle = 'Google Maps';
    public array $settingsForm = [
        'data-address' => [
            'type' => 'text',
            'label' => 'Address',
            'help' => 'Enter the address to show on the map',
            'placeholder' => 'Some Street 1, City, Country',
        ],
        'data-zoom' => [
            'type' => 'slider',
            'label' => 'Zoom',
            'help' => 'Enter zoom level (1-10)',
            'min' => '1',
            'max' => '10',
            'placeholder' => 'Enter zoom',
        ],
    ];


}

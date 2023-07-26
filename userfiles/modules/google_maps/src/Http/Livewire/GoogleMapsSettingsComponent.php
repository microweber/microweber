<?php

namespace MicroweberPackages\Modules\GoogleMaps\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsFormComponent;

class GoogleMapsSettingsComponent extends ModuleSettingsFormComponent
{

    public $moduleTitle = 'Google Maps';
    public array $settingsForm = [
        'data-country' => [
            'type' => 'text',
            'label' => 'Country',
            'help' => 'Country to show on the map',
            'placeholder' => 'United States'
        ],
        'data-city' => [
            'type' => 'text',
            'label' => 'City',
            'help' => 'City to show on the map',
            'placeholder' => 'New York',
        ],
        'data-street' => [
            'type' => 'text',
            'label' => 'Street',
            'help' => 'Street to show on the map',
            'placeholder' => 'Some Street 1',
        ],
        'data-zip' => [
            'type' => 'text',
            'label' => 'Zip',
            'help' => 'Zip to show on the map',
            'placeholder' => '10001',
        ],
        'data-zoom' => [
            'type' => 'range-slider',
            'label' => 'Zoom',
            'help' => 'Enter zoom level (1-10)',
            'attributes' => [
                'min' => 1,
                'max' => 10,
                'step' => 1,
            ],
            'placeholder' => 'Enter zoom',
        ],
        'data-map-type' => [
            'type' => 'dropdown',
            'label' => 'Map type',
            'help' => 'Enter enter map type',
            'options' => [
                'roadmap' => 'Roadmap',
                'satellite' => 'Satellite',
                'hybrid' => 'Hybrid',
                'terrain' => 'Terrain',
            ],
        ],

    ];


}

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
            'attributes' => [
                'label' => 'Enter zoom level (1-10)',
                'min' => 1,
                'max' => 22,
                'step' => 1,
            ],
            'placeholder' => 'Enter zoom',
        ],
        'data-width' => [
            'type' => 'range-slider',
            'label' => 'Width',
            'help' => 'Enter width in pixels',
            'placeholder' => 'Enter width in pixels',
            'attributes' => [
                'label' => 'Enter width in pixels',
                'labelUnit' => 'px',
                'min' => 0,
                'max' => 2000,
                'step' => 1,
            ],
        ],
        'data-height' => [
            'type' => 'range-slider',
            'label' => 'Height',
            'attributes' => [
                'label' => 'Enter height in pixels',
                'labelUnit' => 'px',
                'min' => 0,
                'max' => 2000,
                'step' => 1,
            ],
        ],
//        'data-map-type' => [
//            'type' => 'dropdown',
//            'label' => 'Map type',
//            'help' => 'Enter enter map type',
//            'options' => [
//                'roadmap' => 'Roadmap',
//                'satellite' => 'Satellite',
//                'hybrid' => 'Hybrid',
//                'terrain' => 'Terrain',
//            ],
//        ],

    ];


}

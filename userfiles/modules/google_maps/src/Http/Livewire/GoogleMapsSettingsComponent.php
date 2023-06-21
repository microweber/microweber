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
            'type' => 'slider',
            'label' => 'Zoom',
            'help' => 'Enter zoom level (1-10)',
            'min' => '1',
            'max' => '10',
            'placeholder' => 'Enter zoom',
        ],
        'data-map-type' => [
            'type' => 'dropdown',
            'label' => 'Map type',
            'help' => 'Enter enter map type',
            'values' => [
                'roadmap' => 'Roadmap',
                'satellite' => 'Satellite',
                'hybrid' => 'Hybrid',
                'terrain' => 'Terrain',
            ],
        ],

        'tabs-1' => [
            'type' => 'tabs',
            'tabs' => [
                'tab-1' => [
                    'title' => 'Map photo',
                    'content' => [
                        'data-map-photo' => [
                            'type' => 'dropdown',
                            'translatable' => true,
                            'label' => 'Map type',
                            'help' => 'Enter enter map type',
                            'values' => [
                                'roadmap' => 'Roadmap',
                                'satellite' => 'Satellite',
                                'hybrid' => 'Hybrid',
                                'terrain' => 'Terrain',
                            ],
                            'data-map-photo2' => [
                                'type' => 'dropdown',
                                'label' => 'Map type',
                                'help' => 'Enter enter map type',
                                'values' => [
                                    'roadmap' => 'Roadmap',
                                    'satellite' => 'Satellite',
                                    'hybrid' => 'Hybrid',
                                    'terrain' => 'Terrain',
                                ],
                            ]
                        ],
                    ]

                ],

                'tab-2' => [
                    'title' => 'Map photo 2',
                    'content' => [
                        'data-map-photo2' => [
                            'type' => 'dropdown',
                            'label' => 'Map type',
                            'help' => 'Enter enter map type',
                            'values' => [
                                'roadmap' => 'Roadmap',
                                'satellite' => 'Satellite',
                                'hybrid' => 'Hybrid',
                                'terrain' => 'Terrain',
                            ],
                        ],
                    ]

                ]


            ]
        ]
    ];


}

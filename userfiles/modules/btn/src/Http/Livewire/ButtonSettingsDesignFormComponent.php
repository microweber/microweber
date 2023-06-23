<?php

namespace MicroweberPackages\Modules\Btn\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsFormComponent;

class ButtonSettingsDesignFormComponent extends ModuleSettingsFormComponent
{

    public $moduleTitle = 'Button design';
    public array $settingsForm = [
        'fontSize' => [
            'type' => 'number',
            'label' => 'Font size',
            'help' => 'Font size for the button',
        ],
        'padding' => [
            'type' => 'number',
            'label' => 'Padding',
            'help' => 'Padding for the button',
        ],
        'margin' => [
            'type' => 'number',
            'label' => 'Margin',
            'help' => 'Margin for the button',
        ],

        'backgroundColor' => [
            'type' => 'color',
            'label' => 'Background color',
            'help' => 'Background color for the button',

        ],
        'color' => [
            'type' => 'color',
            'label' => 'Text color',
            'help' => 'Text color for the button',
        ],

        'hoverbackgroundColor' => [
            'type' => 'color',
            'label' => 'Hover background color',
            'help' => 'Hover background color for the button',
        ],
        'hovercolor' => [
            'type' => 'color',
            'label' => 'Hover text color',
            'help' => 'Hover text color for the button',
        ],

        'borderColor' => [
            'type' => 'color',
            'label' => 'Border color',
            'help' => 'Border color for the button',
        ],


        'borderWidth' => [
            'type' => 'number',
            'label' => 'Border width',
            'help' => 'Border width for the button',
        ],

        'borderRadius' => [
            'type' => 'number',
            'label' => 'Border radius',
            'help' => 'Border radius for the button',
        ],

//
// 'backgroundColor' => '',
//        'color' => '',
//        'borderColor' => '',
//        'borderWidth' => '',
//        'borderRadius' => '',
//        'padding' => '',
//        'margin' => '',
//        'fontSize' => '',
//        'shadow' => '',
//        'customSize' => '',
//        'hoverbackgroundColor' => '',
//        'hovercolor' => '',
//        'hoverborderColor' => '',
//
//        'message' => [
//            'type' => 'textarea',
//            'label' => 'textarea link',
//            'help' => 'textarea link for the button',
//            'placeholder' => 'textarea',
//            'content' => [
//                'type' => 'card',
//                'class' => 'alert alert-info',
//                'content' => [
//                    [
//                        'type' => 'card-header',
//                        'content' => 'This is a header'
//
//                    ],
//                    [
//                        'type' => 'card-body',
//                        'content' => 'This is a body'
//
//                    ],
//                    [
//                        'type' => 'card-footer',
//                        'content' => 'This is a footer'
//
//                    ]
//                ]
//            ]
//        ]
    ];





}

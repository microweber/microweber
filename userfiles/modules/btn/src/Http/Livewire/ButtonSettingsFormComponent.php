<?php

namespace MicroweberPackages\Modules\Btn\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsFormComponent;

class ButtonSettingsFormComponent extends ModuleSettingsFormComponent
{

    public $moduleTitle = 'Button settings';
    public array $settingsForm = [
        'text' => [
            'type' => 'text',
            'label' => 'Button text',
            'help' => 'Text for the button',
            'placeholder' => 'Click me',
            'option_key'=> 'dadada',
            'option_group'=> 'dadaad',
        ],
        'url' => [
            'type' => 'text',
            'label' => 'Url link',
            'help' => 'Url link for the button',
            'placeholder' => 'https://google.com'
        ],
        'url_blank' => [
          //  'type' => 'checkbox',
            'type' => 'text',
            'label' => 'Open in new tab',
        ],

//        'align_settings' => [
//            'type' => 'component',
//            'label' => 'Alignment',
//            'component' => 'microweber-module-btn::btn-align',
//        ],
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

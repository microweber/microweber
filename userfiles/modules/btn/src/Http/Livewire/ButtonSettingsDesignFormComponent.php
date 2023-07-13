<?php

namespace MicroweberPackages\Modules\Btn\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsFormComponent;

class ButtonSettingsDesignFormComponent extends ModuleSettingsFormComponent
{

    public $moduleTitle = 'Button design';

    public array $settingsForm = [
        'fontSize' => [
            'type' => 'range-slider',
            'label' => 'Font size',
            'help' => 'Font size for the button',
        ],
        'padding' => [
            'type' => 'range-slider',
            'label' => 'Padding',
            'help' => 'Padding for the button',
        ],
        'margin' => [
            'type' => 'range-slider',
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
            'type' => 'range-slider',
            'label' => 'Border width',
            'help' => 'Border width for the button',
        ],

        'borderRadius' => [
            'type' => 'range-slider',
            'label' => 'Border radius',
            'help' => 'Border radius for the button',
        ],

    ];


}

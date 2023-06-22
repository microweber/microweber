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
            'placeholder' => 'Click me'
        ] ,
        'url' => [
            'type' => 'text',
            'label' => 'Url link',
            'help' => 'Url link for the button',
            'placeholder' => 'https://google.com'
        ]
    ];





    public array $setti22222222ngs = [
        'button_style' => '',
        'button_size' => '',
        'button_action' => '',
        'align' => '',
        'url' => '',
        'url_to_content_id' => '',
        'url_to_category_id' => '',
        'popupcontent' => '',
        'text' => 'Button',
        'url_blank' => '',
        'icon' => '',
        'backgroundColor' => '',
        'color' => '',
        'borderColor' => '',
        'borderWidth' => '',
        'borderRadius' => '',
        'padding' => '',
        'margin' => '',
        'fontSize' => '',
        'shadow' => '',
        'customSize' => '',
        'hoverbackgroundColor' => '',
        'hovercolor' => '',
        'hoverborderColor' => '',
    ];




}

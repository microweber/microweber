<?php

namespace MicroweberPackages\Modules\Btn\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class ButtonSettingsComponent extends ModuleSettingsComponent
{
    public $url = '';
    public array $settings = [
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

    public function render()
    {
        if($this->settings['url_to_content_id'] != ''){
            $this->url = content_link($this->settings['url_to_content_id']);
        }
        if($this->settings['url_to_category_id'] != ''){
            $this->url = category_link($this->settings['url_to_category_id']);
        }
        if($this->settings['url'] != ''){
            $this->url = $this->settings['url'];
        }

        return view('microweber-module-btn::livewire.settings');
    }


}

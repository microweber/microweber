<?php

namespace MicroweberPackages\Modules\LayoutContent\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class LayoutContentDefaultSettingsTemplateComponent extends ModuleSettingsComponent
{

    public array $settings = [

    ];

    public function render()
    {
        return view('microweber-module-layout-content::livewire.default-layout-settings');
    }


}

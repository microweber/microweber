<?php
namespace MicroweberPackages\Modules\Slider\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class SliderSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-slider::livewire.settings');
    }
}

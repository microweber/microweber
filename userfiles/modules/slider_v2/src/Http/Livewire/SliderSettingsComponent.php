<?php
namespace MicroweberPackages\Modules\SliderV2\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class SliderSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-slider-v2::livewire.settings');
    }
}

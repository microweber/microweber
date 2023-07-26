<?php
namespace MicroweberPackages\Modules\Sharer\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class SharerSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-sharer::livewire.settings');
    }
}

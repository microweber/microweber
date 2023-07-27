<?php
namespace MicroweberPackages\Modules\BeforeAfter\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class BeforeAfterSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-before-after::livewire.settings');
    }
}

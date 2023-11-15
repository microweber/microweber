<?php
namespace MicroweberPackages\Modules\CallToAction\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class CallToActionSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-call-to-action::livewire.settings');
    }
}

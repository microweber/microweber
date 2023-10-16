<?php
namespace MicroweberPackages\Modules\UnlockPremiumLayout\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class UnlockPremiumLayoutSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-unlock-premium-layout::livewire.settings');
    }
}

<?php
namespace MicroweberPackages\Modules\UnlockPackage\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class UnlockPackageSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-unlock-package::livewire.settings');
    }
}

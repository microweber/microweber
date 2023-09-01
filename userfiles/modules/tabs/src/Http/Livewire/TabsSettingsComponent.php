<?php
namespace MicroweberPackages\Modules\Tabs\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class TabsSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-tabs::livewire.settings');
    }
}

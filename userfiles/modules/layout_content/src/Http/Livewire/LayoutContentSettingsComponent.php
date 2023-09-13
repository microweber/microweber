<?php
namespace MicroweberPackages\Modules\LayoutContent\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class LayoutContentSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-layout-content::livewire.settings');
    }
}

<?php
namespace MicroweberPackages\Modules\Content\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class ContentSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-content::livewire.settings');
    }
}

<?php
namespace MicroweberPackages\Modules\Categories\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class CategorySettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-category::livewire.settings');
    }
}

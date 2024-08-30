<?php
namespace MicroweberPackages\Modules\Search\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class SearchSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-search::livewire.settings');
    }
}

<?php
namespace MicroweberPackages\Modules\HighlightCode\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class HighlightCodeSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-highlight-code::livewire.settings');
    }
}

<?php
namespace MicroweberPackages\Modules\PDF\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class PDFSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-pdf::livewire.settings');
    }
}

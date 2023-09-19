<?php
namespace MicroweberPackages\Modules\Pdf\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class PdfSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-pdf::livewire.settings');
    }
}

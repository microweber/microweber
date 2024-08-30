<?php
namespace MicroweberPackages\Modules\CustomFields\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class CustomFieldsSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-custom-fields::livewire.settings');
    }
}

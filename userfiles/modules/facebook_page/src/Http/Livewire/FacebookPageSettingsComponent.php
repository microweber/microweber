<?php
namespace MicroweberPackages\Modules\FacebookPage\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class FacebookPageSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-facebook-page::livewire.settings');
    }
}

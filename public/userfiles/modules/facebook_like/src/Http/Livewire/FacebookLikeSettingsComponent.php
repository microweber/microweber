<?php
namespace MicroweberPackages\Modules\FacebookLike\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class FacebookLikeSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-facebook-like::livewire.settings');
    }
}

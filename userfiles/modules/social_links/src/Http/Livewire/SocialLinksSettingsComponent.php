<?php

namespace MicroweberPackages\Modules\SocialLinks\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class SocialLinksSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
        return view('microweber-module-social-links::livewire.settings');
    }
}

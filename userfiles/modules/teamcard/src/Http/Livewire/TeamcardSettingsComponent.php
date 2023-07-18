<?php

namespace MicroweberPackages\Modules\Teamcard\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class TeamcardSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
        return view('microweber-module-teamcard::livewire.settings');
    }

}

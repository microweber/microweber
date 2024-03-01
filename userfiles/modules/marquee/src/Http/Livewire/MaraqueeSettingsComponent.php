<?php

namespace MicroweberPackages\Modules\Maraquee\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class MaraqueeSettingsComponent extends ModuleSettingsComponent
{

    public $showModal = false;

    public function render()
    {
        return view('microweber-module-maraquee::livewire.settings');
    }
}

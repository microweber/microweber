<?php

namespace MicroweberPackages\Modules\TextType\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class TextTypeSettingsComponent extends ModuleSettingsComponent
{

    public function render()
    {
        return view('microweber-module-text-type::livewire.settings');
    }
}

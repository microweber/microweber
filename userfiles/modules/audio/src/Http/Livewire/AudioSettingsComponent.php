<?php
namespace MicroweberPackages\Modules\Audio\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class AudioSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-audio::livewire.settings');
    }
}

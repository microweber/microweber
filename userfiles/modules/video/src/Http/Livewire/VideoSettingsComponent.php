<?php
namespace MicroweberPackages\Modules\Video\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class VideoSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-video::livewire.settings');
    }
}

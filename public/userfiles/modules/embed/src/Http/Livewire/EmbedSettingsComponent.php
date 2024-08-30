<?php
namespace MicroweberPackages\Modules\Embed\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class EmbedSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-embed::livewire.settings');
    }
}

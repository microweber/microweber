<?php
namespace MicroweberPackages\Modules\Posts\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class PostsSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-posts::livewire.settings');
    }
}

<?php
namespace MicroweberPackages\Modules\Categories\CategoryImages\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class CategoryImagesSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-category-images::livewire.settings');
    }
}

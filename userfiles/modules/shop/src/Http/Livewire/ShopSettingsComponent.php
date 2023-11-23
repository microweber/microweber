<?php
namespace MicroweberPackages\Modules\Shop\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class ShopSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-shop::admin.livewire.settings');
    }
}

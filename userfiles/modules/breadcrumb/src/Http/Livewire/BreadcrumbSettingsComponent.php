<?php
namespace MicroweberPackages\Modules\Breadcrumb\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class BreadcrumbSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
       return view('microweber-module-breadcrumb::livewire.settings');
    }
}

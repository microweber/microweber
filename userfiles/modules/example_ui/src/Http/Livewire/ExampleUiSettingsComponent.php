<?php

namespace MicroweberPackages\Modules\ExampleUi\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class ExampleUiSettingsComponent extends ModuleSettingsComponent
{
    public function render()
    {
        return view('microweber-module-example-ui::livewire.settings');
    }

}

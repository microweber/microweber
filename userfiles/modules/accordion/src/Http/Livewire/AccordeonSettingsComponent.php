<?php

namespace MicroweberPackages\Modules\Accordion\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class AccordeonSettingsComponent extends ModuleSettingsComponent
{
    public $listeners = [
   //     'refreshComponent' => '$refresh',
        'mwOptionSave' => 'render',
     ];


    public function render()
    {

        return view('microweber-module-accordion::livewire.settings');
    }

}

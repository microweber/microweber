<?php

namespace MicroweberPackages\LiveEdit\Http\Livewire;

use Livewire\Component;

class ModuleTemplateSelectComponent extends ModuleSettingsComponent
{
    public string $moduleId = '';


    public array $settings = [
        'template' => 'default',
    ];


    public function render()
    {
        return view('live_edit::module_select_template');

     }
}

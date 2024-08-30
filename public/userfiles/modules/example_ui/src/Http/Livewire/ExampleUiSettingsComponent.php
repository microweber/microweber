<?php

namespace MicroweberPackages\Modules\ExampleUi\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class ExampleUiSettingsComponent extends ModuleSettingsComponent
{

    public $showModal = false;
    public $showDialogModal = false;
    public $areYouSureModal = false;

    public function render()
    {
        return view('microweber-module-example-ui::livewire.settings');
    }

    public function showActionMessage()
    {
        $this->dispatch('showActionMessage', message: 'This is a message from Example UI module');
    }
}

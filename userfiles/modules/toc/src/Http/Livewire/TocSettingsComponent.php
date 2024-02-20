<?php

namespace MicroweberPackages\Modules\Toc\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class TocSettingsComponent extends ModuleSettingsComponent
{

    public $showModal = false;
    public $showDialogModal = false;
    public $areYouSureModal = false;

    public function render()
    {
        return view('microweber-module-toc::livewire.settings');
    }

    public function showActionMessage()
    {
        $this->emit('showActionMessage', ['message' => 'This is a message from Example UI module']);
    }
}

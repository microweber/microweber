<?php

namespace MicroweberPackages\Module\Http\Livewire\Admin;

use LivewireUI\Modal\ModalComponent;

class AskForModuleUninstallModal extends ModalComponent
{
    public $moduleId = '';
    public $moduleData = [];

    public function confirm()
    {
        $findModule = \MicroweberPackages\Module\Models\Module::where('id', $this->moduleId)->first();
        if ($findModule) {
            mw()->module_manager->uninstall([
                'id' => $findModule->id,
            ]);
        }

        $this->emit('refreshModuleList');

        $this->closeModal();
    }

    public function mount($moduleId)
    {
        $this->moduleId = $moduleId;
        $this->moduleData = \MicroweberPackages\Module\Models\Module::where('id', $this->moduleId)->first()->toArray();
    }

    public function render()
    {
        return view('module::livewire.admin.ask-for-module-uninstall-modal');
    }
}

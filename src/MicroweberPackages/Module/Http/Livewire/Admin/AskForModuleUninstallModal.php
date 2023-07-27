<?php

namespace MicroweberPackages\Module\Http\Livewire\Admin;

use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;

class AskForModuleUninstallModal extends AdminModalComponent
{
    public $moduleId = '';
    public $moduleData = [];

    public $modalSettings = [
        'width'=>'400px',
        'overlay' => true,
        'overlayClose' => true,
    ];


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

    public function mount($moduleId = false)
    {
        if ($moduleId) {
            $this->moduleId = $moduleId;
            $this->moduleData = \MicroweberPackages\Module\Models\Module::where('id', $this->moduleId)->first()->toArray();
        }
    }

    public function render()
    {
        return view('module::livewire.admin.ask-for-module-uninstall-modal');
    }
}

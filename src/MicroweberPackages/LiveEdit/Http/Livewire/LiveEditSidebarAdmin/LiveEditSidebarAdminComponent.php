<?php

namespace MicroweberPackages\LiveEdit\Http\Livewire\LiveEditSidebarAdmin;

use MicroweberPackages\Admin\Http\Livewire\AdminComponent;

class LiveEditSidebarAdminComponent extends AdminComponent
{
    public string $view = 'microweber-live-edit::sidebar-admin.sidebar-admin-main';

    public $listeners = [
        'onLoaded' => 'populateModulesData',
    ];
    public $modulesData = [];

    public function populateModulesData($modulesData)
    {
dd($modulesData);
        $this->modulesData = $modulesData;
        $this->emit('onModulesDataPopulated', $modulesData);
        $this->render();
    }

    public function render()
    {

        return view($this->view);
    }
}

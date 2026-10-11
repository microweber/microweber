<?php

namespace MicroweberPackages\LiveEdit\Http\Livewire\LiveEditSidebarAdmin;

use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
/**
 * @deprecated
 */
class LiveEditSidebarAdminComponent extends AdminComponent
{
    public string $view = 'microweber-live-edit::sidebar-admin.sidebar-admin-main';

    public $listeners = [
        'onLoaded' => 'populateModulesData',
    ];
    public $modulesData = [];
    public $modulesListKey = 'modulesListKey';

    public function populateModulesData($modulesData)
    {

        $this->modulesData = $modulesData;
        $this->dispatch('onModulesDataPopulated', $modulesData);
        $this->render();
    }

    public function render()
    {
        $this->modulesListKey = md5(json_encode($this->modulesData));

        return view($this->view);
    }
}

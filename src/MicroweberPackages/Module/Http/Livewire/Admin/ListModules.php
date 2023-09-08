<?php

namespace MicroweberPackages\Module\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\Admin\Http\Livewire\AdminComponent;

class ListModules extends AdminComponent
{
    use WithPagination;

    public $keyword;
    public $type = 'admin';
    public $installed = 1;
    public $groupByCategories = false;

    public $queryString = [
        'keyword',
        'page',
        'type',
        'installed',
        'groupByCategories',
    ];

    public $listeners = [
        'refreshModuleList' => '$refresh',
    ];

    public function toggleGroupByCategories()
    {
        $this->groupByCategories = !$this->groupByCategories;
    }

    public function reloadModules()
    {
        mw_post_update();

        $this->dispatchBrowserEvent('mw.admin.modules.reload_list');
    }

    public function filter()
    {
        $this->gotoPage(1);
    }

    public function install($id)
    {
        $findModule = \MicroweberPackages\Module\Models\Module::where('id', $id)->first();

        if ($findModule) {
            mw()->module_manager->set_installed([
                'id' => $findModule->id,
            ]);
        }

        $this->reloadModules();
    }

    public function render()
    {
        $modules = \MicroweberPackages\Module\Models\Module::query();

        if ($this->keyword) {
            $modules->where('name', 'like', '%' . $this->keyword . '%');
        }

        if (!empty($this->type)) {
            if ($this->type == 'elements') {
                $modules->where('as_element', 1);
            }
            if ($this->type == 'admin') {
                $modules->where('ui_admin', 1);
            }
            if ($this->type == 'live_edit') {
                $modules->where('ui', 1);
            }
        }

        $modules->where('installed', $this->installed);

        $modules = $modules->get();

        $modulesGroups = [
            "Content" => [],
            "Store" => [],
            "Online Shop" => [],
            "Admin" => [],
            "Users" => [],
            "Media" => [],
            "Essentials" => [],
            "Miscellaneous" => [],
            "Advanced" => [],
            "Other" => [],
        ];

        if ($this->groupByCategories) {
            foreach ($modules as $module) {
                $modulesGroups[ucwords($module->categories)][] = $module;
            }
        }

        return view('module::livewire.admin.list-modules', [
            'modules' => $modules,
            'modulesGroups' => $modulesGroups,
        ]);
    }
}

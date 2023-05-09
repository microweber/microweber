<?php

namespace MicroweberPackages\Module\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;

class ListModules extends Component
{
    use WithPagination;

    public $keyword;
    public $type = 'admin';
    public $installed = 1;
    public $confirmUnistallId = 1;
    public $groupByCategories = false;

    public $queryString = [
        'keyword',
        'page',
        'type',
        'installed',
    ];

    public function toggleGroupByCategories()
    {
        $this->groupByCategories = !$this->groupByCategories;
    }

    public function reloadModules()
    {
        mw_post_update();
    }

    public function filter()
    {
        $this->gotoPage(1);
    }

    public function confirmUninstall($id)
    {
        $this->confirmUnistallId = $id;
    }

    public function uninstall($id)
    {
        $findModule = \MicroweberPackages\Module\Module::where('id', $id)->first();

        if ($findModule) {
            mw()->module_manager->uninstall([
                'id' => $findModule->id,
            ]);
        }

        $this->reloadModules();
    }

    public function install($id)
    {
        $findModule = \MicroweberPackages\Module\Module::where('id', $id)->first();

        if ($findModule) {
            mw()->module_manager->set_installed([
                'id' => $findModule->id,
            ]);
        }

        $this->reloadModules();
    }

    public function render()
    {
        $modules = \MicroweberPackages\Module\Module::query();

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

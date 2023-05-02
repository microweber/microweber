<?php

namespace MicroweberPackages\Module\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;

class ListModules extends Component
{
    use WithPagination;

    public $keyword = '';

    public $queryString = [
        'keyword',
        'page',
    ];


    public function reloadModules()
    {
        mw_post_update();
    }

    public function filter()
    {
        $this->gotoPage(1);
    }

    public function render()
    {
        $modules = \MicroweberPackages\Module\Module::query();

        if ($this->keyword) {
            $modules->where('name', 'like', '%' . $this->keyword . '%');
        }

        $modules = $modules->paginate(20);

        return view('module::livewire.admin.list-modules', [
            'modules' => $modules
        ]);
    }
}

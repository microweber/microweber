<?php

namespace MicroweberPackages\Module\Http\Livewire\Admin;

use Livewire\Component;

class ListModules extends Component
{
    public $search;

    public function render()
    {
        $modules = \MicroweberPackages\Module\Module::query();

        if ($this->search) {
            $modules->where('name', 'like', '%' . $this->search . '%');
        }

        $modules = $modules->paginate(100);

        return view('module::livewire.admin.list-modules', [
            'modules' => $modules
        ]);
    }
}

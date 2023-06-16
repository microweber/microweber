<?php

namespace MicroweberPackages\Category\Http\Livewire\Admin;

use MicroweberPackages\Admin\Http\Livewire\AdminComponent;

class CategoryManageComponent extends AdminComponent
{
    public $isShop = false;

//    public $selectedIds = [];
//    public $listeners = [
//        'setSelectedIds' => 'setSelectedIds'
//    ];
//
//    public function setSelectedIds($ids)
//    {
//        $this->selectedIds = $ids;
//    }

    public function render()
    {
        return view('category::admin.category.livewire.manage');
    }
}

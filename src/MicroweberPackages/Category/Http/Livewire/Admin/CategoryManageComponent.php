<?php

namespace MicroweberPackages\Category\Http\Livewire\Admin;

use MicroweberPackages\Admin\Http\Livewire\AdminComponent;

class CategoryManageComponent extends AdminComponent
{
    public function render()
    {
        return view('category::admin.category.livewire.manage');
    }
}

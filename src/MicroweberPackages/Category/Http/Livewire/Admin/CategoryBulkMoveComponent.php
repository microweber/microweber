<?php

namespace MicroweberPackages\Category\Http\Livewire\Admin;

use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;

class CategoryBulkMoveComponent extends AdminModalComponent
{
    public function render()
    {
        return view('category::admin.category.livewire.category-bulk-move');
    }
}

<?php

namespace MicroweberPackages\Category\Http\Livewire\Admin;

use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;

class CategoryBulkMoveModalComponent extends AdminModalComponent
{
    public $selectedIds = [];

    public function mount($ids)
    {
        $this->selectedIds = $ids;
    }

    public function render()
    {
        return view('category::admin.category.livewire.category-bulk-move-modal');
    }
}

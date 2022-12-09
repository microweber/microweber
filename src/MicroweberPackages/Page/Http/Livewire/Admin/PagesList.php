<?php

namespace MicroweberPackages\Page\Http\Livewire\Admin;

use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Product\Http\Livewire\Admin\ProductsList;

class PagesList extends ProductsList
{
    public $model = Page::class;

    public $showColumns = [
        'id' => true,
        'image' => true,
        'title' => true,
        'author' => true
    ];

    public function render()
    {
        return view('page::admin.page.livewire.table', [
            'pages' => $this->contents,
            'appliedFilters' => $this->appliedFilters
        ]);
    }

}

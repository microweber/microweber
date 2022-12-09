<?php

namespace MicroweberPackages\Page\Http\Livewire\Admin;

use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Product\Http\Livewire\Admin\ProductsList;
use MicroweberPackages\Product\Models\Product;

class PagesList extends ProductsList
{
    public $model = Page::class;

    public function render()
    {
        return view('page::admin.page.livewire.table', [
            'pages' => $this->contents,
            'appliedFilters' => $this->appliedFilters
        ]);
    }

}

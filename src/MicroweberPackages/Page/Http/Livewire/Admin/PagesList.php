<?php

namespace MicroweberPackages\Page\Http\Livewire\Admin;

use MicroweberPackages\Content\Http\Livewire\Admin\ContentList;
use MicroweberPackages\Page\Models\Page;

class PagesList extends ContentList
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

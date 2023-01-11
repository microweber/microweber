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

}

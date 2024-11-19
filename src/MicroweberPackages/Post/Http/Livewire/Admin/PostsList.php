<?php

namespace MicroweberPackages\Post\Http\Livewire\Admin;

use MicroweberPackages\Content\Http\Livewire\Admin\ContentList;
use Modules\Post\Models\Post;

class PostsList extends ContentList
{
    public $openLinksInModal = false;
    public $model = Post::class;

    public $showColumns = [
        'id' => true,
        'image' => true,
        'title' => true,
        'author' => true
    ];

}

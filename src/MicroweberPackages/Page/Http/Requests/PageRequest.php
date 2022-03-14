<?php
namespace MicroweberPackages\Page\Http\Requests;

use MicroweberPackages\Content\Http\Controllers\Requests\ContentSaveRequest;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Post\Models\Post;

class PageRequest extends ContentSaveRequest
{
    public $model = Page::class;
}

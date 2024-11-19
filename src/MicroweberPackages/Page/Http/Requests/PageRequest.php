<?php
namespace MicroweberPackages\Page\Http\Requests;

use MicroweberPackages\Content\Http\Controllers\Requests\ContentSaveRequest;
use MicroweberPackages\Page\Models\Page;

class PageRequest extends ContentSaveRequest
{
    public $model = Page::class;
}

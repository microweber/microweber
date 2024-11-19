<?php
namespace MicroweberPackages\Page\Http\Requests;

use MicroweberPackages\Content\Http\Controllers\Requests\ContentSaveRequest;
use Modules\Page\Models\Page;

class PageRequest extends ContentSaveRequest
{
    public $model = Page::class;
}

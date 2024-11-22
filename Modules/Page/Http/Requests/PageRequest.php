<?php
namespace Modules\Page\Http\Requests;



use Modules\Content\Http\Requests\ContentSaveRequest;
use Modules\Page\Models\Page;

class PageRequest extends ContentSaveRequest
{
    public $model = Page::class;
}

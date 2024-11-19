<?php
namespace MicroweberPackages\Post\Http\Requests;

use MicroweberPackages\Content\Http\Controllers\Requests\ContentSaveRequest;
use Modules\Post\Models\Post;

class PostRequest extends ContentSaveRequest
{
    public $model = Post::class;
}

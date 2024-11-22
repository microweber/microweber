<?php
namespace Modules\Post\Http\Requests;

use Modules\Content\Http\Requests\ContentSaveRequest;
use Modules\Post\Models\Post;


class PostRequest extends ContentSaveRequest
{
    public $model = Post::class;
}

<?php
namespace MicroweberPackages\Post\Http\Requests;

use MicroweberPackages\Content\Http\Controllers\Requests\ContentSaveRequest;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\Product\Models\Product;

class PostRequest extends ContentSaveRequest
{
    public $model = Post::class;
}

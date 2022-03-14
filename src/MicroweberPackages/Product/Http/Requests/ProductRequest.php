<?php
namespace MicroweberPackages\Product\Http\Requests;

use MicroweberPackages\Content\Content;
use MicroweberPackages\Content\Http\Controllers\Requests\ContentSaveRequest;
use MicroweberPackages\Product\Models\Product;

class ProductRequest extends ContentSaveRequest
{
    public $model = Product::class;
    public $rules = [
        'title' => 'required|max:500',
        'url' => 'max:500',
        'content_meta_title' => 'max:500',
        'content_meta_keywords' => 'max:500',
        'original_link' => 'max:500',
        'price' => 'nullable|price'
    ];

}

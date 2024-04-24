<?php
namespace MicroweberPackages\Product\Http\Requests;

use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Content\Http\Controllers\Requests\ContentSaveRequest;
use MicroweberPackages\Product\Models\Product;

class ProductRequest extends ContentSaveRequest
{
    public $model = Product::class;
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $mainPrice = $this->input('price');
            $specialPrice = $this->input('content_data.special_price');
            if($mainPrice && $specialPrice) {
                if ($specialPrice > $mainPrice) {
                    $validator->errors()->add('content_data.special_price', 'Special price must not be greater than the main price.');
                }
            }
        });
    }
    public $rules = [
        'title' => 'required|max:500',
        'url' => 'max:500',
        'content_meta_title' => 'max:500',
        'content_meta_keywords' => 'max:500',
        'original_link' => 'max:500',
        'price' => 'nullable|numeric',
        'content_data.special_price' => 'nullable|numeric',
        'qty' => 'max:50',
        'sku' => 'max:500',
        'content_data.barcode' => 'max:200',
        'content_data.track_quantity' => 'max:200',
        'content_data.sell_oos' => 'max:200',
        'content_data.qty' => 'max:200',
        'content_data.max_qty_per_order' => 'max:200',
        'content_data.physical_product' => 'max:200',
        'content_data.shipping_fixed_cost' => 'max:200',
        'content_data.weight' => 'max:200',
        'content_data.weight_type' => 'max:200',
        'content_data.free_shipping' => 'max:200',
        'content_data.width' => 'max:200',
        'content_data.height' => 'max:200',
        'content_data.depth' => 'max:200',
        'content_data.label' => 'max:200',
        'content_data.label-color' => 'max:200',
    ];

}

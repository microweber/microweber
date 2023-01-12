<?php

namespace MicroweberPackages\Product\Http\Livewire\Admin;

use MicroweberPackages\Content\Http\Livewire\Admin\ContentList;
use MicroweberPackages\Product\Models\Product;

class ProductsList extends ContentList
{
    public $model = Product::class;

    public $whitelistedEmptyKeys = ['inStock', 'orders', 'qty'];

    public $showColumns = [
        'id' => true,
        'image' => true,
        'title' => true,
        'price' => true,
        'stock' => true,
        'orders' => true,
        'quantity' => true,
        'author' => false
    ];

}


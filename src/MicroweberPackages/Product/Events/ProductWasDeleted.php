<?php

namespace MicroweberPackages\Product\Events;

use MicroweberPackages\Product\Product;

class ProductWasDeleted
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}

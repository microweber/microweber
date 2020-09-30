<?php

namespace MicroweberPackages\Product\Events;

use MicroweberPackages\Product\Product;

class ProductIsUpdating
{
    private $product;
    private $request;

    public function __construct(Product $product, $request)
    {
        $this->product = $product;
        $this->request = $request;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function getRequest()
    {
        return $this->request;
    }
}

<?php

namespace MicroweberPackages\Product\Events;

use MicroweberPackages\Product\Product;

class ProductWasCreated
{
    private $product;
    private $request;

    public function __construct(Product $product, $request)
    {
        $this->product = $product;
        $this->request = $request;
    }

    public function getModel()
    {
        return $this->product;
    }

    public function getRequest()
    {
        return $this->request;
    }

}

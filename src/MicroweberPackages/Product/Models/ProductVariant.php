<?php
namespace MicroweberPackages\Product\Models;

class ProductVariant extends Product
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->attributes['content_type'] = 'product_variant';
        $this->attributes['subtype'] = 'product_variant';
    }
}

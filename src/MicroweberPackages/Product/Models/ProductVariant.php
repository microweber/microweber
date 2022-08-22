<?php
namespace MicroweberPackages\Product\Models;

use MicroweberPackages\Content\Scopes\ProductVariantScope;
use MicroweberPackages\ContentDataVariant\Traits\ContentDataVariantTrait;

class ProductVariant extends Product
{

    use ContentDataVariantTrait;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->attributes['content_type'] = 'product_variant';
        $this->attributes['subtype'] = 'product_variant';
    }


    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new ProductVariantScope());
    }
}

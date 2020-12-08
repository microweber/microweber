<?php
namespace MicroweberPackages\Product\Models;

/**
 * @deprecated
 */


use MicroweberPackages\CustomField\Models\CustomField;
use MicroweberPackages\Product\Scopes\PriceScope;

class ProductPrice extends CustomField
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new PriceScope());
    }

    public function save(array $options = [])
    {
        $this->rel_type = 'content';
        $this->type = 'price';

       return parent::save($options);
    }
}
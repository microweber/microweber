<?php
namespace MicroweberPackages\CustomField;


use MicroweberPackages\CustomField\Scopes\PriceScope;

class CustomFieldPrice extends CustomField
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
        $this->name = 'price';
        $this->name_key = 'price';

        return parent::save($options);
    }
}
<?php

namespace MicroweberPackages\Product\Traits;

use MicroweberPackages\Product\Models\ProductPrice;

trait CustomFieldPriceTrait {

    private $_addCustomFields = [];
    private $_addPriceField = null;
    private $_addSpecialPriceField = null;

    public function initializeCustomFieldPriceTrait()
    {
        $this->fillable[] = 'price'; // related with boot custom fields trait
        $this->fillable[] = 'special_price'; // related with boot custom fields trait
    }

    public static function bootCustomFieldPriceTrait()
    {
        static::saving(function ($model)  {
            if (isset($model->attributes['price'])) {
                $model->_addPriceField = $model->attributes['price'];
                unset($model->attributes['price']);
            }
            if (isset($model->attributes['special_price'])) {
                $model->_addSpecialPriceField = $model->attributes['special_price'];
                unset($model->attributes['special_price']);
            }
        });

        static::saved(function($model) {

            if (isset($model->_addPriceField)) {

                $price = ProductPrice::where('rel_id', $model->id)->where('rel_type',$model->getMorphClass())->first();

                if (!$price) {
                    $price = new ProductPrice();
                }

                $priceInputVal = trim($model->_addPriceField);
                if(is_numeric($priceInputVal)) {
                    $price->value = $priceInputVal;
                } else {
                    $price->value = floatval($priceInputVal);
                }

                $price->rel_id = $model->id;
                $price->save();
            }

        });
    }


}
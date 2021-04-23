<?php

namespace MicroweberPackages\Product\Traits;

use MicroweberPackages\Product\Models\ProductPrice;

trait CustomFieldPriceTrait
{

    private $_addPriceField = null;
    private $_removePriceField = null;
    private $_addSpecialPriceField = null;

    public function initializeCustomFieldPriceTrait()
    {
        $this->fillable[] = 'price'; // related with boot custom fields trait
        $this->fillable[] = 'special_price'; // related with boot custom fields trait
    }

    public static function bootCustomFieldPriceTrait()
    {

        static::saving(function ($model) {

            if ($model->attributes and array_key_exists("price", $model->attributes)) {
                if (isset($model->attributes['price'])) {
                    $model->_addPriceField = $model->attributes['price'];
                } else {
                    $model->_removePriceField = true;
                }
                unset($model->attributes['price']);

            }
            if ($model->attributes and array_key_exists("special_price", $model->attributes)) {
                if (isset($model->attributes['special_price'])) {
                    $model->_addSpecialPriceField = $model->attributes['special_price'];
                }
                unset($model->attributes['special_price']);
            }
        });

        static::saved(function ($model) {
            if (isset($model->_addPriceField)) {

                $price = ProductPrice::where('rel_id', $model->id)->where('rel_type', $model->getMorphClass())->first();

                if (!$price) {
                    $price = new ProductPrice();
                    $price->name = 'price';
                    $price->name_key = 'price';
                }

                $priceInputVal = trim($model->_addPriceField);
                if (is_numeric($priceInputVal)) {
                    $price->value = $priceInputVal;
                } else {
                    $price->value = floatval($priceInputVal);
                }

                $price->rel_id = $model->id;
                $price->save();
            }

            if (isset($model->_removePriceField) and $model->_removePriceField) {
                $price = ProductPrice::where('rel_id', $model->id)->where('rel_type', $model->getMorphClass())->first();
                if($price){
                    $price->delete();
                }

            }

        });
    }


}
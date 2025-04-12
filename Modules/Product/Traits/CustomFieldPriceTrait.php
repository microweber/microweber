<?php

namespace Modules\Product\Traits;

use Modules\Offer\Models\Offer;
use Modules\Product\Models\ProductPrice;

trait CustomFieldPriceTrait
{

    public $_addPriceField = null;
    public $_removePriceField = null;
    public $_addSpecialPriceField = null;
    public $_removeSpecialPriceField = null;

    public function initializeCustomFieldPriceTrait()
    {
        $this->fillable[] = 'price'; // related with boot custom fields trait
        $this->fillable[] = 'special_price'; // related with boot custom fields trait
     //  $this->appends[] = 'price'; // related with boot custom fields trait
    //   $this->appends[] = 'special_price'; // related with boot custom fields trait

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
                } else {
                    $model->_removeSpecialPriceField = true;
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
                $price->rel_type = $model->getMorphClass();
                $price->save();
            }

            if (isset($model->_removePriceField) and $model->_removePriceField) {
                $price = ProductPrice::where('rel_id', $model->id)->where('rel_type', $model->getMorphClass())->first();
                if($price){
                    $price->delete();
                }

            }


            if(function_exists('offers_get_price')) {


                if ($model->_addSpecialPriceField) {
                    $offerPrice = $model->_addSpecialPriceField;
                    $productId = $model->id;
                    $priceId = $model->priceModel->id;

                    $query = Offer::where('price_id', $priceId);
                    if ($productId) {
                        $query->where('product_id', '=', $productId);
                    }
                    $findOffer = $query->first();

                    $saveOffer = array();
                    if ($findOffer) {
                        $saveOffer['id'] = $findOffer->id;
                    }

                    $saveOffer['offer_price'] = $offerPrice;
                    $saveOffer['product_id_with_price_id'] = $productId . '|' . $priceId;

                    Offer::add($saveOffer);
                } else {
                    //    $query = Offer::where('product_id', '=', $productModel->id)->delete();
                    if (isset($model->_removeSpecialPriceField)) {
                        $model = Offer::where('product_id', '=', $model->id)->delete();
                    }
                }
            }
        });
    }


}

<?php
/**
 * Created by PhpStorm.
 * Page: Bojidar
 * Date: 8/19/2020
 * Time: 2:53 PM
 */

namespace MicroweberPackages\Product\Observers;

use MicroweberPackages\Product\Models\Product;
use MicroweberPackages\Product\Models\ProductPrice;
use MicroweberPackages\Product\Models\ProductSpecialPrice;

class ProductObserver
{
    protected static $fieldsToSave = [];


    public function retrieved(Product $model)
    {
      /* foreach($model->getAttributes() as $attributeKey=>$attributeValue) {
           unset($model->$attributeKey);
       }*/


    }

    public function saving(Product $product) {

        $product->content_type = 'product';

        if (isset($product->price)) {
            self::$fieldsToSave['price'] = $product->price;
            unset($product->price);
        }

        if (isset($product->special_price)) {
            self::$fieldsToSave['special_price'] = $product->special_price;
            unset($product->special_price);
        }
    }


    /**
     * Handle the Page "saving" event.
     *
     * @param  \MicroweberPackages\Product\Models\Product  $product
     * @return void
     */
    public function saved(Product $product)
    {
        if (isset(self::$fieldsToSave['price'])) {
            $price = ProductPrice::where('rel_id', $product->id)->first();
            if (!$price) {
                $price = new ProductPrice();
            }

            $price->value = self::$fieldsToSave['price'];
            $price->rel_id = $product->id;
            $price->save();
        }

        if (isset(self::$fieldsToSave['special_price'])) {

            $specialPrice = ProductSpecialPrice::where('rel_id', $product->id)->first();
            if (!$specialPrice) {
                $specialPrice = new ProductSpecialPrice();
            }

            $specialPrice->value = self::$fieldsToSave['special_price'];
            $specialPrice->rel_id = $product->id;
            $specialPrice->save();
        }
        
        cache_delete('content');
    }
}
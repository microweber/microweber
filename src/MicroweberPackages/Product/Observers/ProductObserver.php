<?php
/**
 * Created by PhpStorm.
 * Page: Bojidar
 * Date: 8/19/2020
 * Time: 2:53 PM
 */

namespace MicroweberPackages\Product\Observers;

use MicroweberPackages\CustomField\CustomFieldPrice;
use MicroweberPackages\CustomField\CustomFieldSpecialPrice;
use MicroweberPackages\Product\Product;

class ProductObserver
{
    protected static $fieldsToSave = [];

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
     * @param  \MicroweberPackages\Product\Product  $product
     * @return void
     */
    public function saved(Product $product)
    {
        if (isset(self::$fieldsToSave['price'])) {
            $price = CustomFieldPrice::where('rel_id', $product->id)->first();
            if (!$price) {
                $price = new CustomFieldPrice();
            }

            $price->value = self::$fieldsToSave['price'];
            $price->rel_id = $product->id;
            $price->save();
        }

        if (isset(self::$fieldsToSave['special_price'])) {

            $specialPrice = CustomFieldSpecialPrice::where('rel_id', $product->id)->first();
            if (!$specialPrice) {
                $specialPrice = new CustomFieldSpecialPrice();
            }

            $specialPrice->value = self::$fieldsToSave['special_price'];
            $specialPrice->rel_id = $product->id;
            $specialPrice->save();
        }
        
        cache_delete('content');
    }
}
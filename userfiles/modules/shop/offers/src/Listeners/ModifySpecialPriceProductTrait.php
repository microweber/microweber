<?php

/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/1/2020
 * Time: 11:13 AM
 */

namespace MicroweberPackages\Shop\Offers\Listeners;

use Illuminate\Support\Facades\DB;

trait ModifySpecialPriceProductTrait {

    public function handle($event)
    {
        $data = $event->getData();
        $product = $event->getModel();

        if (!isset($product->priceModel->id)) {
            return;
        }

        if (isset($data['content_data']['special_price'])) {

            $productId = $product->id;
            $priceId = $product->priceModel->id;

            $offer = DB::table('offers')->select('*');
            if ($productId) {
                $findOffer = $offer->where('product_id', '=', $productId);
            }
            $findOffer = $findOffer->where('price_id', '=', $priceId);
            $findOffer = $findOffer->first();

            $saveOffer = array();
            if ($findOffer) {
                $saveOffer['id'] = $findOffer->id;
            }

            if(empty($data['content_data']['has_special_price'])) {
                //removed special price
                $saveOffer['is_active'] = 0;
             } else {
                // set special price
                $saveOffer['is_active'] = 1;
            }

            $saveOffer['offer_price'] = $data['content_data']['special_price'];
            $saveOffer['product_id_with_price_id'] = $productId . '|'.$priceId;
            $save = offer_save($saveOffer);
        }


    }
}
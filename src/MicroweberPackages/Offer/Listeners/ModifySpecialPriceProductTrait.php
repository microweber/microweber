<?php

namespace MicroweberPackages\Offer\Listeners;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Offer\Models\Offer;

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

            $query = Offer::where('price_id', $priceId);
            if ($productId) {
                $query->where('product_id', '=', $productId);
            }
            $findOffer = $query->first();

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

            Offer::add($saveOffer);
        }
    }
}

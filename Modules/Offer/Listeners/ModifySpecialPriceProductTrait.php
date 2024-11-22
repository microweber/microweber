<?php

namespace Modules\Offer\Listeners;

use Modules\Offer\Models\Offer;

trait ModifySpecialPriceProductTrait {

    public function handle($event)
    {
        $data = $event->getData();
        $product = $event->getModel();

        if (!isset($product->priceModel->id)) {
            return;
        }
        if (isset($data['content_data']['special_price'])) {

            $this->saveSpecialPriceToModel($product, $data);
        }


    }

    public function saveSpecialPriceToModel($productModel, $data){
        if (isset($data['content_data']['special_price'])) {

            $productId = $productModel->id;
            $priceId = $productModel->priceModel->id;

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

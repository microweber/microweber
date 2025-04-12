<?php

namespace Modules\Offer\Listeners;

use Modules\Offer\Models\Offer;

trait ModifySpecialPriceProductTrait
{

    public function handle($event)
    {

        //moved to Modules/Product/Traits/CustomFieldPriceTrait.php as the listener is not always called by some reason

        return;

        $data = $event->getData();
        $product = $event->getModel();

        if (!isset($product->priceModel)) {
            return;
        }
        if (isset($product->_removeSpecialPriceField)) {
            $query = Offer::where('product_id', '=', $product->id)->delete();
        }
        if (!isset($product->_addSpecialPriceField)) {
            return;
        }


        $this->saveSpecialPriceToModel($product, $product->_addSpecialPriceField);


    }

    public function saveSpecialPriceToModel($productModel, $offerPrice)
    {
        if ($productModel->_addSpecialPriceField) {

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

            $saveOffer['offer_price'] = $offerPrice;
            $saveOffer['product_id_with_price_id'] = $productId . '|' . $priceId;

            Offer::add($saveOffer);
        } else {
        //    $query = Offer::where('product_id', '=', $productModel->id)->delete();

        }
    }
}

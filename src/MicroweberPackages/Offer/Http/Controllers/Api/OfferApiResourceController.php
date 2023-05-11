<?php

namespace MicroweberPackages\Offer\Http\Controllers\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\ValidationException;
use MicroweberPackages\App\Http\Controllers\Controller;
use MicroweberPackages\Offer\Http\Requests\OfferCreateUpdateRequest;

use MicroweberPackages\Offer\Models\Offer;
use Illuminate\Http\Request;

class OfferApiResourceController extends Controller
{
    public function store(OfferCreateUpdateRequest $request)
    {
        $offerData = $request->all();
        if (isset($offerData['product_id_with_price_id'])) {
            $id_parts = explode('|', $offerData['product_id_with_price_id']);
            $offerData['product_id'] = $id_parts[0];
        } else if (isset($offerData['product_id'])) {
            if (strstr($offerData['product_id'], '|')) {
                $id_parts = explode('|', $offerData['product_id']);
                $offerData['product_id'] = $id_parts[0];
            }
        }
        if (isset($offerData['offer_price'])) {
            $offerData['offer_price'] = mw()->format->amount_to_float($offerData['offer_price']);
        }
        $productPrice = (float) get_product_price($offerData['product_id']);
        if ($offerData['offer_price'] >= $productPrice) {
            throw ValidationException::withMessages(['offer_price' => 'This offer price must be lower than the product price']);
        }

        $offer = Offer::add($offerData);

        $json = [];
        $json['offer_id'] = isset($offer->id) ? $offer->id : null;
        $json['success_edit'] = isset($offer->id);

        return (new JsonResource($json))->response();


    }


    public function destroy(Request $request)
    {
        $delete = Offer::deleteById($request->offer_id);
        if ($delete) {
            $res = ['status' => 'success'];
        } else {
            $res = ['status' => 'failed'];
        }

        return (new JsonResource($res))->response();

    }
}

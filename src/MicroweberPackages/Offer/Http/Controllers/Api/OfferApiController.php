<?php

namespace MicroweberPackages\Offer\Http\Controllers\Api;

use MicroweberPackages\Offer\Http\Requests\OfferCreateUpdateRequest;
use App\Http\Controllers\Controller;
use MicroweberPackages\Offer\Models\Offer;
use Illuminate\Http\Request;

class OfferApiController extends Controller
{
    public function store(OfferCreateUpdateRequest $request)
    {
        $offer = Offer::add($request->all());

        $json = [];
        $json['offer_id'] = isset($offer->id) ? $offer->id : null;
        $json['success_edit'] = isset($offer->id);

        return $json;
    }

    public function index()
    {
        $offers = Offer::getAll();

        return $offers;
    }

    public function getByProductId($productId)
    {
        $offers = Offer::getByProductId($productId);

        return $offers;
    }

    public function destroy(Request $request)
    {
        $delete = Offer::deleteById($request->offer_id);

        if($delete) {
            $res = ['status' => 'success'];
        } else {
            $res = ['status' => 'failed'];
        }

        return $res;
    }
}
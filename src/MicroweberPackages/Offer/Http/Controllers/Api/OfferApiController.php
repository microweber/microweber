<?php

namespace MicroweberPackages\Offer\Http\Controllers\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\App\Http\Controllers\Controller;
use MicroweberPackages\Offer\Http\Requests\OfferCreateUpdateRequest;

use MicroweberPackages\Offer\Models\Offer;
use Illuminate\Http\Request;

class OfferApiController extends Controller
{


    public function index()
    {
        $offers = Offer::getAll();

        return $offers;
    }

    public function getByProductId($productId = false)
    {
        if (!$productId) {
            return [];
        }

        $offers = Offer::getByProductId($productId);

        return $offers;
    }


}

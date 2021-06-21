<?php

namespace MicroweberPackages\Product\Http\Controllers;

use Illuminate\Http\Request;
use MicroweberPackages\Product\Models\Product;

class ProductQuickViewController
{
    public function view(Request $request) {

        $productId = $request->get('id', false);
        if ($productId) {
            $findProduct = Product::where('id', $productId)->first();
            if ($findProduct !== null) {
                return [
                    'id'=>$findProduct->id,
                    'url'=>$findProduct->url,
                    'title'=>$findProduct->title,
                    'description'=>$findProduct->description,
                    'shortDescription'=>$findProduct->shortDescription(200),
                    'inStock'=>$findProduct->inStock,
                    'sku'=>$findProduct->sku,
                    'model'=>$findProduct->model,
                    'qty'=>$findProduct->qty,
                    'price'=>$findProduct->price,
                ];
            }
        }

        return [];

    }
}

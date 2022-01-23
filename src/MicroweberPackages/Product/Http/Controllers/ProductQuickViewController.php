<?php

namespace MicroweberPackages\Product\Http\Controllers;

use Illuminate\Http\Request;
use MicroweberPackages\Product\Models\Product;

class ProductQuickViewController
{
    public function view(Request $request) {

        $product = false;
        $productAsArray = [];

        $productId = $request->get('id', false);
        if ($productId) {
            $product = Product::where('id', $productId)->first();
            if ($product !== null) {
                $productAsArray =  [
                    'id'=>$product->id,
                    'url'=>$product->url,
                  //  'thumbnail'=>$product->thumbnail(800,800, true),
                    'thumbnail'=>app()->content_repository->getThumbnail($product->id,800,800, true),
                    'title'=>$product->title,
                    'description'=>$product->description,
                    'shortDescription'=>$product->shortDescription(200),
                    'inStock'=>$product->inStock,
                    'sku'=>$product->sku,
                    'model'=>$product->model,
                    'qty'=>$product->qty,
                    'price'=>$product->price,
                ];
            }
        }

        $json = $request->get('json', false);
        if ($json){
            return $productAsArray;
        }

        return view('shop::product-quick-view', compact('product'));
    }
}

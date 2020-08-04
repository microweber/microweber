<?php

namespace MicroweberPackages\Product;

class RecentlyViewed
{
    public function store($product)
    {
        /*$product->load('reviews');

        $this->remove($product->id);

        return $this->add([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->selling_price->amount(),
            'quantity' => 1,
            'attributes' => compact('product'),
        ]);*/
    }

    public function products()
    {
        /*return $this->getContent()->map(function ($item) {
            return $item->attributes->product;
        });*/
    }
}

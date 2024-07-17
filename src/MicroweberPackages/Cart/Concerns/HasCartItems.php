<?php

namespace MicroweberPackages\Cart\Concerns;

use MicroweberPackages\Cart\Models\Cart;
use MicroweberPackages\Product\Models\Product;

trait HasCartItems
{


    protected static function bootHasCartItems()
    {
//
//        static::saving(function ($model) {
//            dd($model);
//         });
//
//        static::creating(function ($model) {
//
//        });
//        static::updating(function ($model) {
//
//        });
        static::deleting(function ($model) {
            $model->cart()->delete();
        });

    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }


}

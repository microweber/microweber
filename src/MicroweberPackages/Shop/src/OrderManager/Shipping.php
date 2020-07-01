<?php
namespace MicroweberPackages\OrderManager;

class Shipping extends Illuminate\Database\Eloquent\Model
{
    public $table = 'cart_shipping';

    public static function boot()
    {
        parent::boot();
    }
}

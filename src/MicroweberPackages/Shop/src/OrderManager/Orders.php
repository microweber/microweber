<?php
namespace MicroweberPackages\OrderManager;

class Orders extends Illuminate\Database\Eloquent\Model
{
    public $table = 'cart_orders';

    public static function boot()
    {
        parent::boot();
    }
}

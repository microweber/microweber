<?php


class Order extends BaseModel
{
    public $table = 'cart_orders';

    public static function boot()
    {
        parent::boot();
    }
}

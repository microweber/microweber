<?php


class Shipping extends BaseModel
{
    public $table = 'cart_shipping';

    public static function boot()
    {
        parent::boot();
    }
}

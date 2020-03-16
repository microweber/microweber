<?php
namespace Microweber;

class Cart extends BaseModel
{
    public $table = 'cart';

    public static function boot()
    {
        parent::boot();
    }
}

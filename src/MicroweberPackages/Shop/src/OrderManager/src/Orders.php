<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber LTD
 *
 * For full license information see
 * http://Microweber.com/license/
 *
 */

namespace MicroweberPackages\Shop\OrderManager;

class Orders extends Illuminate\Database\Eloquent\Model
{
    public $table = 'cart_orders';

    public static function boot()
    {
        parent::boot();
    }
}

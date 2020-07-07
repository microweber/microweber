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

class Shipping extends Illuminate\Database\Eloquent\Model
{
    public $table = 'cart_shipping';

    public static function boot()
    {
        parent::boot();
    }
}

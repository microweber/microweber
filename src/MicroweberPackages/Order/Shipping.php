<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Order;

class Shipping extends Illuminate\Database\Eloquent\Model
{
    public $table = 'cart_shipping';

    public static function boot()
    {
        parent::boot();
    }
}

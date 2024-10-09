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

namespace Modules\Shipping\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * Class ShippingToCountry
 *
 * @deprecated
 */
class ShippingToCountry extends Model
{
    use Notifiable;

    public $table = 'cart_shipping';

    public static function boot()
    {
        parent::boot();
    }
}

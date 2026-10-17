<?php

namespace Modules\Coupons\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;

class CartCoupon extends Model
{
    use FiresModelLifecycleEvents;

    protected $table = 'cart_coupons';
}

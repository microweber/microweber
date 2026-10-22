<?php

namespace Modules\Coupons\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;

class CartCoupon extends Model
{

    /**
     * Fire lifecycle events so other modules can hook this model's changes.
     */
    protected $dispatchesEvents = [
        'created' => \Modules\Coupons\Events\CartCouponWasCreated::class,
        'updated' => \Modules\Coupons\Events\CartCouponWasUpdated::class,
        'deleted' => \Modules\Coupons\Events\CartCouponWasDeleted::class,
    ];
    use FiresModelLifecycleEvents;

    protected $table = 'cart_coupons';
}

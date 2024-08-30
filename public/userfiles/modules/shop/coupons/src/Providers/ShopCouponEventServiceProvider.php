<?php

namespace MicroweberPackages\Modules\Shop\Coupons\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as EventServiceProviderBase;
use MicroweberPackages\Modules\Shop\Coupons\Listeners\OrderWasCreatedCouponCodeLogger;
use MicroweberPackages\Order\Events\OrderWasCreated;

class ShopCouponEventServiceProvider extends EventServiceProviderBase
{
    protected $listen = [
        OrderWasCreated::class => [
            OrderWasCreatedCouponCodeLogger::class
        ],
    ];
}

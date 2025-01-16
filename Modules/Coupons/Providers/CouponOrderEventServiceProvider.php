<?php

namespace Modules\Coupons\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Modules\Coupons\Listeners\OrderWasCreatedCouponCodeLogger;
use Modules\Order\Events\OrderWasCreated;

class CouponOrderEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        OrderWasCreated::class => [
            OrderWasCreatedCouponCodeLogger::class
        ],
    ];
}

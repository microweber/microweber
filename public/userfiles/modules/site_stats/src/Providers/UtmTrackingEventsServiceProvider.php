<?php

namespace MicroweberPackages\Modules\SiteStats\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use MicroweberPackages\Modules\SiteStats\Listeners\AddPaymentInfoListener;
use MicroweberPackages\Modules\SiteStats\Listeners\AddShippingInfoListener;
use MicroweberPackages\Modules\SiteStats\Listeners\AddToCartListener;
use MicroweberPackages\Modules\SiteStats\Listeners\BeginCheckoutListener;
use MicroweberPackages\Modules\SiteStats\Listeners\OrderWasPaidListener;
use MicroweberPackages\Modules\SiteStats\Listeners\RemoveFromCartListener;
use MicroweberPackages\Modules\SiteStats\Listeners\UserWasLoggedListener;
use MicroweberPackages\Modules\SiteStats\Listeners\UserWasRegisteredListener;
use Modules\Cart\Events\AddToCartEvent;
use Modules\Cart\Events\RemoveFromCartEvent;
use Modules\Checkout\Events\AddPaymentInfoEvent;
use Modules\Checkout\Events\AddShippingInfoEvent;
use Modules\Checkout\Events\BeginCheckoutEvent;
use Modules\Order\Events\OrderWasPaid;

class UtmTrackingEventsServiceProvider extends EventServiceProvider
{
    protected $listen = [
        Login::class => [
            UserWasLoggedListener::class
        ],
        Registered::class => [
            UserWasRegisteredListener::class,
        ],
        AddToCartEvent::class => [
            AddToCartListener::class
        ],
        RemoveFromCartEvent::class => [
            RemoveFromCartListener::class
        ],
        BeginCheckoutEvent::class => [
            BeginCheckoutListener::class
        ],
        AddPaymentInfoEvent::class => [
            AddPaymentInfoListener::class
        ],
        AddShippingInfoEvent::class => [
            AddShippingInfoListener::class
        ],
        OrderWasPaid::class => [
            OrderWasPaidListener::class
        ],
//        OrderWasCreated::class => [
//            OrderWasCreatedListener::class
//        ],
    ];


}

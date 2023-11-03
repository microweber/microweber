<?php

namespace MicroweberPackages\Modules\SiteStats\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\Cart\Events\AddToCartEvent;
use MicroweberPackages\Cart\Events\RemoveFromCartEvent;
use MicroweberPackages\Checkout\Events\AddPaymentInfoEvent;
use MicroweberPackages\Checkout\Events\AddShippingInfoEvent;
use MicroweberPackages\Checkout\Events\BeginCheckoutEvent;
use MicroweberPackages\Modules\SiteStats\Listeners\AddPaymentInfoListener;
use MicroweberPackages\Modules\SiteStats\Listeners\AddShippingInfoListener;
use MicroweberPackages\Modules\SiteStats\Listeners\AddToCartListener;
use MicroweberPackages\Modules\SiteStats\Listeners\BeginCheckoutListener;
use MicroweberPackages\Modules\SiteStats\Listeners\OrderWasPaidListener;
use MicroweberPackages\Modules\SiteStats\Listeners\RemoveFromCartListener;
use MicroweberPackages\Modules\SiteStats\Listeners\UserWasLoggedListener;
use MicroweberPackages\Modules\SiteStats\Listeners\UserWasRegisteredListener;
use MicroweberPackages\Order\Events\OrderWasPaid;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\SiteStats\Http\Livewire\SiteStatsSettingsComponent;

class SiteStatsEventsServiceProvider extends EventServiceProvider
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
    ];


}

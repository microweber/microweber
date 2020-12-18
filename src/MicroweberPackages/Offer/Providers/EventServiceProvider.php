<?php

namespace MicroweberPackages\Offer\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use MicroweberPackages\Product\Events\ProductWasCreated;
use MicroweberPackages\Product\Events\ProductWasUpdated;
use MicroweberPackages\Offer\Listeners\AddSpecialPriceProductListener;
use MicroweberPackages\Offer\Listeners\EditSpecialPriceProductListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        ProductWasCreated::class => [
            AddSpecialPriceProductListener::class
        ],
        ProductWasUpdated::class => [
            EditSpecialPriceProductListener::class
        ]
    ];
}
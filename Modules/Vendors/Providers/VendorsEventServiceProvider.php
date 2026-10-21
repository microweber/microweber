<?php

namespace Modules\Vendors\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Modules\Order\Events\OrderWasPaid;
use Modules\Order\Events\OrderWasUpdated;
use Modules\Vendors\Listeners\NotifyVendorsOnOrderPaid;
use Modules\Vendors\Listeners\NotifyVendorsOnOrderUpdated;

class VendorsEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        OrderWasPaid::class => [
            NotifyVendorsOnOrderPaid::class,
        ],
        // Admin "mark paid" toggle fires OrderWasUpdated, not OrderWasPaid.
        OrderWasUpdated::class => [
            NotifyVendorsOnOrderUpdated::class,
        ],
    ];
}

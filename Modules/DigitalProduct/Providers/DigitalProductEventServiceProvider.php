<?php

namespace Modules\DigitalProduct\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Modules\DigitalProduct\Listeners\GrantDownloadsOnOrderPaid;
use Modules\DigitalProduct\Listeners\GrantDownloadsOnOrderUpdated;
use Modules\DigitalProduct\Listeners\GrantFreeDownloadsOnOrderCreated;
use Modules\Order\Events\OrderWasCreated;
use Modules\Order\Events\OrderWasPaid;
use Modules\Order\Events\OrderWasUpdated;

class DigitalProductEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        OrderWasPaid::class => [
            GrantDownloadsOnOrderPaid::class,
        ],
        OrderWasCreated::class => [
            GrantFreeDownloadsOnOrderCreated::class,
        ],
        // Covers marking an order paid manually in the admin (is_paid toggle),
        // which fires OrderWasUpdated rather than OrderWasPaid.
        OrderWasUpdated::class => [
            GrantDownloadsOnOrderUpdated::class,
        ],
    ];
}

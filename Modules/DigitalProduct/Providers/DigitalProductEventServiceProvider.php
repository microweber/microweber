<?php

namespace Modules\DigitalProduct\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Modules\DigitalProduct\Listeners\GrantDownloadsOnOrderPaid;
use Modules\DigitalProduct\Listeners\GrantFreeDownloadsOnOrderCreated;
use Modules\Order\Events\OrderWasCreated;
use Modules\Order\Events\OrderWasPaid;

class DigitalProductEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        OrderWasPaid::class => [
            GrantDownloadsOnOrderPaid::class,
        ],
        OrderWasCreated::class => [
            GrantFreeDownloadsOnOrderCreated::class,
        ],
    ];
}

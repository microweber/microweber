<?php

namespace Modules\DigitalProduct\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Modules\DigitalProduct\Listeners\GrantDownloadsOnOrderPaid;
use Modules\Order\Events\OrderWasPaid;

class DigitalProductEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        OrderWasPaid::class => [
            GrantDownloadsOnOrderPaid::class,
        ],
    ];
}

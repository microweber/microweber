<?php

namespace Modules\Booking\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Modules\Booking\Listeners\ConfirmBookingsOnOrderPaid;
use Modules\Order\Events\OrderWasPaid;
use Modules\Order\Events\OrderWasUpdated;

class BookingEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        OrderWasPaid::class => [
            ConfirmBookingsOnOrderPaid::class,
        ],
        // Admin "mark paid" toggle fires OrderWasUpdated, not OrderWasPaid.
        OrderWasUpdated::class => [
            ConfirmBookingsOnOrderPaid::class,
        ],
    ];
}

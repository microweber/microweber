<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Order\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use MicroweberPackages\Order\Events\OrderWasCreated;
use MicroweberPackages\Order\Events\OrderWasPaid;
use MicroweberPackages\Order\Listeners\OrderCreatedListener;
use MicroweberPackages\Order\Listeners\OrderWasPaidListener;
use MicroweberPackages\Order\Listeners\PaymentListener;
use Modules\Payment\Events\PaymentWasCreated;
use Modules\Payment\Events\PaymentWasDeleted;
use Modules\Payment\Events\PaymentWasUpdated;

class OrderEventServiceProvider extends EventServiceProvider
{

    protected $listen = [
        OrderWasCreated::class => [
            OrderCreatedListener::class
        ],
        OrderWasPaid::class => [
            OrderWasPaidListener::class
        ],
        PaymentWasCreated::class=>[
            PaymentListener::class
        ],
        PaymentWasUpdated::class=>[
            PaymentListener::class
        ],
        PaymentWasDeleted::class=>[
            PaymentListener::class
        ]
    ];

}


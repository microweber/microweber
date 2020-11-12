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

namespace MicroweberPackages\Customer\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use MicroweberPackages\Customer\Listeners\CreateCustomerFromOrderListener;
use MicroweberPackages\Order\Events\OrderWasCreated;

class CustomerEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        OrderWasCreated::class => [
            CreateCustomerFromOrderListener::class
        ]
    ];
}


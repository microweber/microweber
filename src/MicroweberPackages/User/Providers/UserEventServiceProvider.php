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

namespace MicroweberPackages\User\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use MicroweberPackages\User\Listeners\RecordFailedLoginAttemptListener;
use MicroweberPackages\User\Listeners\UserRegisteredListener;
use MicroweberPackages\User\Events\UserWasRegistered;

class UserEventServiceProvider extends EventServiceProvider
{

    protected $listen = [
        UserWasRegistered::class => [
            UserRegisteredListener::class
        ],
        \Illuminate\Auth\Events\Failed::class => [
            RecordFailedLoginAttemptListener::class,
        ],
    ];
}

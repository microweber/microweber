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

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use MicroweberPackages\User\Listeners\RecordAuthenticatedLoginListener;
use MicroweberPackages\User\Listeners\RecordFailedLoginAttemptListener;
use MicroweberPackages\User\Listeners\UserRegisteredListener;

class UserEventServiceProvider extends EventServiceProvider
{

    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            UserRegisteredListener::class
        ],
        \Illuminate\Auth\Events\Failed::class => [
            RecordFailedLoginAttemptListener::class,
        ],
        \Illuminate\Auth\Events\Login::class => [
            RecordAuthenticatedLoginListener::class,
        ]
    ];
}

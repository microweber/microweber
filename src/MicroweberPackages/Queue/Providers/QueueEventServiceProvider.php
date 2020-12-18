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

namespace MicroweberPackages\Queue\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use MicroweberPackages\Queue\Events\ProcessQueueEvent;
use MicroweberPackages\Queue\Listeners\ProcessQueueListener;
use MicroweberPackages\User\Listeners\RecordAuthenticatedLoginListener;
use MicroweberPackages\User\Listeners\RecordFailedLoginAttemptListener;
use MicroweberPackages\User\Listeners\UserRegisteredListener;

class QueueEventServiceProvider extends EventServiceProvider
{

    protected $listen = [
        ProcessQueueEvent::class => [
            ProcessQueueListener::class
        ]

    ];
}

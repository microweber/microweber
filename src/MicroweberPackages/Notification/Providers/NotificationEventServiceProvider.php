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

namespace MicroweberPackages\Notification\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use MicroweberPackages\Notification\Listeners\UserRegisteredListener;
use MicroweberPackages\User\Events\UserWasRegistered;

class NotificationEventServiceProvider extends EventServiceProvider
{
    

}


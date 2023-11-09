<?php

namespace MicroweberPackages\Modules\SiteStats\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use MicroweberPackages\Modules\SiteStats\Listeners\UserWasRegisteredLocalListener;

class SiteStatsEventsLocalTrackingServiceProvider extends EventServiceProvider
{
    protected $listen = [
        Registered::class => [
            UserWasRegisteredLocalListener::class,
        ],
    ];
}

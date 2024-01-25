<?php

namespace MicroweberPackages\Modules\GoogleAnalytics\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use MicroweberPackages\Modules\GoogleAnalytics\Listeners\GoogleAnalyticsPingStatsListener;
use MicroweberPackages\SiteStats\Events\PingStatsEvent;

class GoogleAnalyticsEventsServiceProvider extends EventServiceProvider
{
    protected $listen = [
        PingStatsEvent::class => [
            GoogleAnalyticsPingStatsListener::class
        ]
    ];
}

<?php
namespace MicroweberPackages\Modules\GoogleAnalytics\Listeners;

class GoogleAnalyticsPingStatsListener
{
    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        dd($event);
    }
}

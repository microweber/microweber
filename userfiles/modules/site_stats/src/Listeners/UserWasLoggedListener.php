<?php
namespace MicroweberPackages\Modules\SiteStats\Listeners;

use MicroweberPackages\SiteStats\Models\StatsEvent;

class UserWasLoggedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        // ...
    }

    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        $newStatsEvent = new StatsEvent();
        $newStatsEvent->event_category = 'user';
        $newStatsEvent->event_action = 'logged';
        $newStatsEvent->event_label = 'User logged';
        $newStatsEvent->event_value = 1;
        $newStatsEvent->utm_source = 'user';
        $newStatsEvent->utm_medium = 'logged';
        $newStatsEvent->utm_campaign = 'logged';
        $newStatsEvent->utm_term = 'logged';
        $newStatsEvent->utm_content = 'logged';
        $newStatsEvent->event_timestamp = date('Y-m-d H:i:s');
        $newStatsEvent->save();

    }
}

<?php
namespace MicroweberPackages\Modules\SiteStats\Listeners;

use MicroweberPackages\SiteStats\Models\StatsEvent;

class UserWasRegisteredListener
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
        $newStatsEvent->event_action = 'register';
        $newStatsEvent->event_label = 'User registered';
        $newStatsEvent->event_value = 1;
        $newStatsEvent->utm_source = 'user';
        $newStatsEvent->utm_medium = 'register';
        $newStatsEvent->utm_campaign = 'register';
        $newStatsEvent->utm_term = 'register';
        $newStatsEvent->utm_content = 'register';
        $newStatsEvent->event_timestamp = date('Y-m-d H:i:s');
        $newStatsEvent->save();

    }
}

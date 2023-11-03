<?php
namespace MicroweberPackages\Modules\SiteStats\Listeners;

use MicroweberPackages\SiteStats\Models\StatsEvent;

class BeginCheckoutListener
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
        $newStatsEvent->event_category = 'checkout';
        $newStatsEvent->event_action = 'begin_checkout';
        $newStatsEvent->event_label = 'Checkout started';
        $newStatsEvent->event_value = 1;
        $newStatsEvent->utm_source = 'checkout';
        $newStatsEvent->utm_medium = 'start';
        $newStatsEvent->utm_campaign = 'start';
        $newStatsEvent->utm_term = 'start';
        $newStatsEvent->utm_content = 'start';
        $newStatsEvent->event_data = json_encode($event);
        $newStatsEvent->event_timestamp = date('Y-m-d H:i:s');
        $newStatsEvent->save();

    }
}

<?php
namespace MicroweberPackages\Modules\SiteStats\Listeners;

use MicroweberPackages\SiteStats\Models\StatsEvent;

class OrderWasPaidListener
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
        $newStatsEvent->event_category = 'order';
        $newStatsEvent->event_action = 'purchase';
        $newStatsEvent->event_label = 'Order was paid';
        $newStatsEvent->event_value = 1;
        $newStatsEvent->utm_source = 'order';
        $newStatsEvent->utm_medium = 'paid';
        $newStatsEvent->utm_campaign = 'paid';
        $newStatsEvent->utm_term = 'paid';
        $newStatsEvent->utm_content = 'paid';
        $newStatsEvent->event_data = json_encode($event);
        $newStatsEvent->event_timestamp = date('Y-m-d H:i:s');
        $newStatsEvent->save();

    }
}

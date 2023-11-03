<?php
namespace MicroweberPackages\Modules\SiteStats\Listeners;

use MicroweberPackages\SiteStats\Models\StatsEvent;

class AddShippingInfoListener
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
        $newStatsEvent->event_category = 'shipping';
        $newStatsEvent->event_action = 'add_shipping_info';
        $newStatsEvent->event_label = 'Shipping added';
        $newStatsEvent->event_value = 1;
        $newStatsEvent->utm_source = 'shipping';
        $newStatsEvent->utm_medium = 'add';
        $newStatsEvent->utm_campaign = 'add';
        $newStatsEvent->utm_term = 'add';
        $newStatsEvent->utm_content = 'add';
        $newStatsEvent->event_timestamp = date('Y-m-d H:i:s');
        $newStatsEvent->save();

    }
}

<?php
namespace MicroweberPackages\Modules\SiteStats\Listeners;

use MicroweberPackages\SiteStats\Models\StatsEvent;

class AddToCartListener
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
        $newStatsEvent->event_category = 'Cart Actions';
        $newStatsEvent->event_action = 'Add to Cart';
        $newStatsEvent->event_label = $event->product['title'];
        $newStatsEvent->event_value = $event->product['qty'];
        $newStatsEvent->utm_source = 'cart';
        $newStatsEvent->utm_medium = 'add';
        $newStatsEvent->utm_campaign = 'add';
        $newStatsEvent->utm_term = 'add';
        $newStatsEvent->utm_content = 'add';
        $newStatsEvent->session_id = app()->user_manager->session_id();
        $newStatsEvent->event_data = json_encode($event);
        $newStatsEvent->event_timestamp = date('Y-m-d H:i:s');
        $newStatsEvent->save();

    }
}

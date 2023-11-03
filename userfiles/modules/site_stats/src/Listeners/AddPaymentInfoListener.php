<?php
namespace MicroweberPackages\Modules\SiteStats\Listeners;

use MicroweberPackages\SiteStats\Models\StatsEvent;

class AddPaymentInfoListener
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
        $newStatsEvent->event_category = 'payment';
        $newStatsEvent->event_action = 'add_payment_info';
        $newStatsEvent->event_label = 'Payment info';
        $newStatsEvent->event_value = 1;
        $newStatsEvent->utm_source = 'payment';
        $newStatsEvent->utm_medium = 'info';
        $newStatsEvent->utm_campaign = 'info';
        $newStatsEvent->utm_term = 'info';
        $newStatsEvent->utm_content = 'info';
        $newStatsEvent->event_timestamp = date('Y-m-d H:i:s');
        $newStatsEvent->save();

    }
}

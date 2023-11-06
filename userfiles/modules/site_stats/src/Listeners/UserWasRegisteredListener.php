<?php
namespace MicroweberPackages\Modules\SiteStats\Listeners;

use MicroweberPackages\SiteStats\Models\StatsEvent;
use MicroweberPackages\SiteStats\UtmVisitorData;

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
        $visitorData = UtmVisitorData::getVisitorData();

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
        $newStatsEvent->session_id = app()->user_manager->session_id();

        if (isset($visitorData['utm_visitor_id'])) {
            $newStatsEvent->utm_visitor_id = $visitorData['utm_visitor_id'];
        }

        if (isset($visitorData['utm_source'])) {
            $newStatsEvent->utm_source = $visitorData['utm_source'];
        }

        $newStatsEvent->event_data = json_encode($event);
        $newStatsEvent->event_timestamp = date('Y-m-d H:i:s');
        $newStatsEvent->save();

    }
}

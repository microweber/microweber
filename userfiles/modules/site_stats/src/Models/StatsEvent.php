<?php
namespace MicroweberPackages\SiteStats\Models;


use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Modules\SiteStats\DTO\UtmEvent;
use MicroweberPackages\SiteStats\UtmVisitorData;

class StatsEvent extends Model
{
    protected $table = 'stats_events';

    public static function saveNewUtm(UtmEvent $event) {

        $visitorData = UtmVisitorData::getVisitorData();

        $newStatsEvent = new StatsEvent();
        $newStatsEvent->event_category = $event->eventCategory;
        $newStatsEvent->event_action = $event->eventAction;
        $newStatsEvent->event_label = $event->eventLabel;
        $newStatsEvent->event_value = $event->eventValue;
        $newStatsEvent->utm_source = '';
        $newStatsEvent->utm_medium = '';
        $newStatsEvent->utm_campaign = '';
        $newStatsEvent->utm_term = '';
        $newStatsEvent->utm_content = '';

        $newStatsEvent->session_id = app()->user_manager->session_id();

        if (isset($visitorData['utm_visitor_id'])) {
            $newStatsEvent->utm_visitor_id = $visitorData['utm_visitor_id'];
        }

        if (isset($visitorData['utm_source'])) {
            $newStatsEvent->utm_source = $visitorData['utm_source'];
        }

        $newStatsEvent->event_data = json_encode($event->eventData);
        $newStatsEvent->event_timestamp = date('Y-m-d H:i:s');
        $newStatsEvent->save();

    }

}

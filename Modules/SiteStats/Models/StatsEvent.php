<?php
namespace Modules\SiteStats\Models;


use Illuminate\Database\Eloquent\Model;
use Modules\SiteStats\DTO\UtmEvent;
use Modules\SiteStats\Support\UtmVisitorData;

class StatsEvent extends Model
{
    protected $table = 'stats_events';
    
    protected $fillable = [
        'event_category',
        'event_action', 
        'event_label',
        'event_value',
        'user_id',
        'session_id',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'utm_visitor_id',
        'event_data',
        'event_timestamp'
    ];

    public static function saveNewUtm(UtmEvent $event) {


        try {

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
            $newStatsEvent->user_id = app()->user_manager->id();
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


        } catch (\Exception $e) {
            $event->eventData = '';
            $event->error = $e->getMessage();
        }





    }

}

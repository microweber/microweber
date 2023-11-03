<?php

namespace MicroweberPackages\SiteStats;

use MicroweberPackages\SiteStats\Models\StatsEvent;

class DispatchServerSideTracking
{
    public $visitorId = '';
    public $session_id = '';

    public function setVisitorId($id)
    {
        $this->visitorId = $id;
    }

    public function setSessionId($id)
    {
        $this->session_id = $id;
    }

    public function dispatch()
    {

        $analytics = new Analytics();

        $analytics->setProtocolVersion('1')
            ->setTrackingId(get_option('google-analytics-id', 'website'))
            ->setClientId($this->visitorId);


        $sendPageView = $analytics->setEventCategory('PageView')
            ->setEventAction('PageView')
            ->setEventLabel('PageView')
            ->setEventValue(1)
            ->sendEvent();

        dd($sendPageView);

        $getStatsEvents = StatsEvent::where('session_id', $this->session_id)->get();
        if ($getStatsEvents->count() > 0) {
            foreach ($getStatsEvents as $getStatsEvent) {


                dd($getStatsEvent);

              //  $getStatsEvent->delete();
            }

        }
    }
}

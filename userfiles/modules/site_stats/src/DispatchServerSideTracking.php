<?php

namespace MicroweberPackages\SiteStats;

use AlexWestergaard\PhpGa4\Analytics;
use AlexWestergaard\PhpGa4\Event\PageView;
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

        $measurementId = get_option('google-measurement-id', 'website');
        $apiSecret = get_option('google-measurement-api-secret', 'website');

        dd($apiSecret);

        $analytics = Analytics::new(
            $measurementId, $apiSecret
        );
        $analytics->setClientId($this->visitorId);

        $event = PageView::new()
            ->setPageTitle('OIT ->' . rand(111,999))
            ->setPageLocation(site_url() . '/123' . rand(111,999))
            ->setPageReferrer('https://google.com')
            ->setLanguage('en-us')
            ->setScreenResolution(1920, 1080);

        $analytics->addEvent($event);
        $send = $analytics->post();

        $getStatsEvents = StatsEvent::where('session_id', $this->session_id)->get();
        if ($getStatsEvents->count() > 0) {
            foreach ($getStatsEvents as $getStatsEvent) {

                dump($getStatsEvent);

              //  $getStatsEvent->delete();
            }

        }
    }
}

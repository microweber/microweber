<?php
namespace MicroweberPackages\Modules\GoogleAnalytics\Listeners;

use MicroweberPackages\Modules\GoogleAnalytics\DispatchGoogleServerSideTracking;
use MicroweberPackages\SiteStats\UtmVisitorData;

class GoogleAnalyticsPingStatsListener
{
    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        if (isset($_COOKIE['_ga'])) {
            $isGoogleMesurementEnabled = get_option('google-measurement-enabled', 'website') == "y";
            if ($isGoogleMesurementEnabled) {
                UtmVisitorData::setVisitorData([
                    'utm_source' => 'google',
                    'utm_visitor_id' => $_COOKIE['_ga']
                ]);
                $googleServerSideTracking = new DispatchGoogleServerSideTracking();
                $googleServerSideTracking->dispatch();

            }
        }
    }
}

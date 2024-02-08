<?php
namespace MicroweberPackages\Modules\SiteStats\Listeners;

use MicroweberPackages\Modules\SiteStats\DTO\UtmEventConversion;
use MicroweberPackages\Modules\SiteStats\DTO\UtmEventPurchase;
use MicroweberPackages\Modules\SiteStats\Models\StatsEvent;

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
        $utmEvent = new UtmEventPurchase();
        $utmEvent->setInternalData($event);
        StatsEvent::saveNewUtm($utmEvent);

        $utmEvent = new UtmEventConversion();
        $utmEvent->setInternalData($event);
        StatsEvent::saveNewUtm($utmEvent);
    }
}

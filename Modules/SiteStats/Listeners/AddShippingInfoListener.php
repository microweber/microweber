<?php
namespace Modules\SiteStats\Listeners;

use Modules\SiteStats\DTO\UtmEventAddShippingInfo;
use Modules\SiteStats\Models\StatsEvent;

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
        $utmEvent = new UtmEventAddShippingInfo();
        $utmEvent->setInternalData($event);

        StatsEvent::saveNewUtm($utmEvent);
    }
}

<?php
namespace MicroweberPackages\Modules\SiteStats\Listeners;

use MicroweberPackages\Modules\SiteStats\DTO\UtmEventActionBeginCheckout;
use MicroweberPackages\SiteStats\Models\StatsEvent;

class BeginCheckoutListener
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
        $utmEvent = new UtmEventActionBeginCheckout();
        $utmEvent->setInternalData($event);

        StatsEvent::saveNewUtm($utmEvent);
    }
}

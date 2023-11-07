<?php
namespace MicroweberPackages\Modules\SiteStats\Listeners;

use MicroweberPackages\SiteStats\Models\StatsEvent;

class RemoveFromCartListener
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
//        $utmEvent = new UtmEvent();
//        $utmEvent->setInternalData($event);
//
//        StatsEvent::saveNewUtm($utmEvent);

    }
}

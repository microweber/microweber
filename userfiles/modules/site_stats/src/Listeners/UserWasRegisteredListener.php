<?php
namespace MicroweberPackages\Modules\SiteStats\Listeners;

use MicroweberPackages\Modules\SiteStats\DTO\UtmEvent;
use MicroweberPackages\Modules\SiteStats\DTO\UtmEventSignUp;
use MicroweberPackages\Modules\SiteStats\DispatchLocalTracking;
use MicroweberPackages\Modules\SiteStats\Models\StatsEvent;
use MicroweberPackages\Modules\SiteStats\UtmVisitorData;

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
        $utmEvent = new UtmEventSignUp();
        $utmEvent->setInternalData($event);

        StatsEvent::saveNewUtm($utmEvent);
    }
}

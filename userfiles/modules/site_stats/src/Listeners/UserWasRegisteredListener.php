<?php
namespace MicroweberPackages\Modules\SiteStats\Listeners;

use MicroweberPackages\Modules\SiteStats\DTO\UtmEvent;
use MicroweberPackages\SiteStats\Models\StatsEvent;
use MicroweberPackages\SiteStats\UtmVisitorData;

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
        $utmEvent = new UtmEvent();
        $utmEvent->setEventAction(UtmEvent::DTO_EVENT_ACTION_SIGN_UP);
        $utmEvent->setEventCategory('user');
        $utmEvent->setEventLabel('User registered');
        $utmEvent->setEventCategory($event);

        StatsEvent::saveNewUtm($utmEvent);
    }
}

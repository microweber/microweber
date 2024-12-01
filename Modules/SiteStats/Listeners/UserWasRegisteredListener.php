<?php
namespace Modules\SiteStats\Listeners;

use Modules\SiteStats\DTO\UtmEventSignUp;
use Modules\SiteStats\Models\StatsEvent;

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

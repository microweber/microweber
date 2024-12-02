<?php
namespace Modules\SiteStats\Listeners;

use Modules\SiteStats\DTO\UtmEventLogin;
use Modules\SiteStats\Models\StatsEvent;

class UserWasLoggedListener
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
        $utmEvent = new UtmEventLogin();
        $utmEvent->setInternalData($event);

        StatsEvent::saveNewUtm($utmEvent);
    }
}

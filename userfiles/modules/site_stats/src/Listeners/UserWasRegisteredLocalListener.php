<?php
namespace MicroweberPackages\Modules\SiteStats\Listeners;

use MicroweberPackages\Modules\SiteStats\DTO\UtmEvent;
use MicroweberPackages\Modules\SiteStats\DTO\UtmEventSignUp;
use MicroweberPackages\SiteStats\DispatchLocalTracking;
use MicroweberPackages\SiteStats\Models\StatsEvent;
use MicroweberPackages\SiteStats\UtmVisitorData;

class UserWasRegisteredLocalListener
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
        $localTracking = new DispatchLocalTracking();
        $localTracking->userId = $event->user->id;
        $localTracking->dispatch();
    }
}

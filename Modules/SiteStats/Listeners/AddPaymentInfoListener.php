<?php
namespace Modules\SiteStats\Listeners;

use Modules\SiteStats\DTO\UtmEventAddPaymentInfo;
use Modules\SiteStats\Models\StatsEvent;

class AddPaymentInfoListener
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
        $utmEvent = new UtmEventAddPaymentInfo();
        $utmEvent->setInternalData($event);

        StatsEvent::saveNewUtm($utmEvent);
    }
}

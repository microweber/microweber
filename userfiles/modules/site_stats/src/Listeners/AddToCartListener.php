<?php
namespace MicroweberPackages\Modules\SiteStats\Listeners;

use MicroweberPackages\Modules\SiteStats\DTO\UtmEventAddToCart;
use MicroweberPackages\SiteStats\Models\StatsEvent;

class AddToCartListener
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

        $utmEvent = new UtmEventAddToCart();
        $utmEvent->setInternalData($event);

        StatsEvent::saveNewUtm($utmEvent);

    }
}

<?php
namespace Modules\SiteStats\Listeners;

use Modules\SiteStats\DTO\UtmEventConversion;
use Modules\SiteStats\DTO\UtmEventPurchase;
use Modules\SiteStats\Models\StatsEvent;

class OrderWasCreatedListener
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
        // Handle order creation event
    }
}

<?php
namespace Modules\SiteStats\Listeners;

use Modules\SiteStats\Events\DispatchLocalTracking;

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

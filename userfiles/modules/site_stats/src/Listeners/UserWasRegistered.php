<?php
namespace MicroweberPackages\Modules\SiteStats\Listeners;

class UserWasRegistered
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
        echo 2222;
        dd($event);
    }
}

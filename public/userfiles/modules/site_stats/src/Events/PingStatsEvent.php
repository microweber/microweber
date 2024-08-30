<?php
namespace MicroweberPackages\Modules\SiteStats\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PingStatsEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $eventData;

    /**
     * Create a new event instance.
     */
    public function __construct($eventData)
    {
        $this->eventData = $eventData;
    }

}

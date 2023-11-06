<?php

namespace MicroweberPackages\Checkout\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddShippingInfoEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $shippingDetails;

    /**
     * Create a new event instance.
     */
    public function __construct($shippingDetails) {
        $this->shippingDetails = $shippingDetails;
    }

}

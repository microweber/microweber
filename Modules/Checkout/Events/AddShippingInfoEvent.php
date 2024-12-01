<?php

namespace Modules\Checkout\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddShippingInfoEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cartData;

    /**
     * Create a new event instance.
     */
    public function __construct($cartData) {
        $this->cartData = $cartData;
    }

}

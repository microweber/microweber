<?php

namespace MicroweberPackages\Checkout\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BeginCheckoutEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cartData;

    /**
     * Create a new event instance.
     */
    public function __construct($cartData)
    {

        $this->cartData = $cartData;

    }

}

<?php

namespace MicroweberPackages\Checkout\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddPaymentInfoEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $paymentInfo;

    /**
     * Create a new event instance.
     */
    public function __construct($paymentInfo) {
        $this->paymentInfo = $paymentInfo;
    }

}

<?php

namespace Modules\Order\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderWasPaid
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $utmData;

    public function __construct($order, $utmData = null)
    {
        $this->order = $order;
        $this->utmData = $utmData;
    }

}

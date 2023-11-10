<?php

namespace MicroweberPackages\Order\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderWasPaid
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct($model)
    {
        $this->model = $model;
    }

}

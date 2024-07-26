<?php

namespace MicroweberPackages\Order\Listeners;



use MicroweberPackages\Order\Models\Order;

class PaymentListener
{

    public function handle($event)
    {
        if ($event->model->rel_type !== Order::class) {
            return;
        }

        $findOrder = Order::where('id', $event->model->rel_id)->first();
        if ($findOrder) {
            $findOrder->calculateNewAmounts();
        }

    }
}

<?php

namespace MicroweberPackages\Order\Listeners;


use MicroweberPackages\Order\Listeners\Tratis\NewOrderNotificationTrait;

class OrderCreatedListener
{
    use NewOrderNotificationTrait;

    public function handle($event)
    {
        $order = $event->getModel();

        $sendWhen = Option::getValue('order_email_send_when', 'orders');
        if ($sendWhen == 'order_paid') {
            $this->sendNewOrderNotification($order);
        }
    }
}

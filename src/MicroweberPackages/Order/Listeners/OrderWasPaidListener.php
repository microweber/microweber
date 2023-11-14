<?php

namespace MicroweberPackages\Order\Listeners;


use MicroweberPackages\Option\Facades\Option;
use MicroweberPackages\Order\Listeners\Tratis\NewOrderNotificationTrait;

class OrderWasPaidListener
{
    use NewOrderNotificationTrait;

    public function handle($event)
    {
        $sendWhen = Option::getValue('order_email_send_when', 'orders');

        if ($sendWhen == 'order_paid') {
            $this->sendNewOrderNotification($event);
        }
    }
}

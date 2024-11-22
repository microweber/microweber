<?php

namespace Modules\Order\Listeners;


use MicroweberPackages\Option\Facades\Option;
use Modules\Order\Listeners\Tratis\NewOrderNotificationTrait;

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

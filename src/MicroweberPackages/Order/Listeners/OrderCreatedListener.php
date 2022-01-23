<?php

namespace MicroweberPackages\Order\Listeners;


use Illuminate\Support\Facades\Notification;
use MicroweberPackages\Option\Facades\Option;
use MicroweberPackages\Order\Listeners\Tratis\NewOrderNotificationTrait;
use MicroweberPackages\Order\Notifications\NewOrderNotification;
use MicroweberPackages\User\Models\User;

class OrderCreatedListener
{
    use NewOrderNotificationTrait;

    public function handle($event)
    {
        $order = $event->getModel();

        $sendWhen = Option::getValue('order_email_send_when', 'orders');
        if ($sendWhen == 'order_received') {
            $this->sendNewOrderNotification($order);
        }

        // Admin panel notification
        Notification::send(User::whereIsAdmin(1)->get(), new NewOrderNotification($order));
    }
}

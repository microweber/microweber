<?php

namespace MicroweberPackages\Order\Listeners;

use Illuminate\Support\Facades\Notification;
use MicroweberPackages\Order\Models\OrderAnonymousClient;
use MicroweberPackages\User\Models\User;
use MicroweberPackages\Order\Notifications\NewOrderNotification;

class OrderCreatedListener
{
    public function handle($event)
    {
       // $data = $event->getData();
        $order = $event->getModel();

        $orderId = $order->id;

        $newOrderEvent = new NewOrderNotification($order);

        // Ss logged
        $notifiable = false;
        if (isset($order->created_by) && $order->created_by > 0) {
            $customer = User::where('id', $order->created_by)->first();
            if ($customer) {
                if (empty($order->email)) {
                    $notifiable = $customer;
                }
            }
        }

        if (!$notifiable) {
            $notifiable = OrderAnonymousClient::find($orderId);
        }

        if ($notifiable) {
            $notifiable->notifyNow($newOrderEvent);
        }

        Notification::send(User::whereIsAdmin(1)->get(), $newOrderEvent);

    }
}

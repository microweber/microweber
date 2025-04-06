<?php

namespace Modules\Billing\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Laravel\Cashier\Billable;
use Modules\Customer\Models\Customer;

class SubscriptionCustomer extends Customer
{
//    use Billable;
//    use Notifiable;

    public $table = 'customers';

//    public function routeNotificationForMail(Notification $notification): array|string
//    {
//        return $this->email();
//    }
}

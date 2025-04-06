<?php

namespace Modules\Billing\Models;

class SubscriptionItem extends \Laravel\Cashier\SubscriptionItem
{
    protected $table = 'subscription_items';
}

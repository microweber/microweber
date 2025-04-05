<?php

namespace Modules\Billing\Models\Stripe;

class SubscriptionItem extends \Laravel\Cashier\SubscriptionItem
{
    protected $table = 'subscription_items_stripe';
}

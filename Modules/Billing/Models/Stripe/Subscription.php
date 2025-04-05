<?php

namespace Modules\Billing\Models\Stripe;

use Illuminate\Support\Collection;
use Modules\Billing\Models\SubscriptionPlan;

class Subscription extends \Laravel\Cashier\Subscription
{
    protected $table = 'subscriptions_stripe';

    public function getNameAttribute()
    {
        $stripePriceId = $this->stripe_price;
        $findPlan = SubscriptionPlan::where('remote_provider_price_id', $stripePriceId)->first();
        if ($findPlan) {
            return $findPlan->name;
        }

        return '';
    }

    public function getUsernameAttribute()
    {
        return user_name($this->owner()->first()->user_id);
    }

    public function getEmailAttribute()
    {
        return user_email($this->owner()->first()->user_id);
    }

}

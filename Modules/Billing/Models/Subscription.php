<?php

namespace Modules\Billing\Models;

class Subscription extends \Laravel\Cashier\Subscription
{
    protected $table = 'subscriptions';


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

    public function isActive()
    {
        return $this->stripe_status === 'active';

    }

    public function isExpired()
    {
        return $this->stripe_status === 'expired';

    }
    /**
     * Get the subscription plan associated with this subscription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }
}

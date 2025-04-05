<?php

namespace Modules\Billing\Models;

use App\Models\User;

class BillingUser extends User
{

    protected $table = 'users';

//    protected $fillable = [
//        'subscription_plan_id',
//        'auto_activate_free_trial_after_date',
//        'activate_free_trial_after_date'
//    ];

    public function getActiveSubscription(string $sku = 'hosting'): ?array
    {
        return getUserActiveSubscriptionPlanBySKU($this->id, $sku);
    }

    public function getSubscriptionName(): string
    {
        $activeSubscription = $this->getActiveSubscription();
        return $activeSubscription ? $activeSubscription['name'] : 'No active subscription';
    }

    public function hasActiveSubscription(): bool
    {
        return (bool) $this->getActiveSubscription();
    }

    public function subscriptionManual()
    {
        return $this->hasOne(SubscriptionManual::class, 'user_id');
    }
}

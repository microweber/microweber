<?php

namespace Modules\Billing\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    public $timestamps = false;

    protected $table = 'subscription_plans';

    protected $casts = [
        'plan_data' => 'array',
    ];

    protected $fillable = [
        'name',
        'sku',
        'group_id',
        'remote_provider',
        'remote_provider_price_id',
        'price',
        'discount_price',
        'save_price',
        'save_price_badge',
        'auto_apply_coupon_code',
        'billing_interval',
        'alternative_annual_plan_id',
        'description',
        'sort_order',

    ];

    public function features()
    {
        return $this->hasMany(SubscriptionPlanFeature::class);
    }

    public function group()
    {
        return $this->belongsTo(SubscriptionPlanGroup::class, 'group_id');
    }

    /**
     * Calculate the yearly price based on billing interval/cycle
     *
     * @return float
     */
    public function yearlyPrice()
    {
        // Check which field exists and has a value
        $billingPeriod = $this->billing_interval ?? 'monthly';

        // If billing period is yearly, return the price as-is
        if ($billingPeriod === 'yearly') {
            return $this->price;
        }

        // If billing period is monthly, multiply by 12 for yearly equivalent
        return $this->price * 12;
    }
}

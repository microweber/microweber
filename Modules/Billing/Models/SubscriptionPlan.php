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
        'display_price',
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
}

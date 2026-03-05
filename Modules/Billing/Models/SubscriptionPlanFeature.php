<?php

namespace Modules\Billing\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlanFeature extends Model
{
    public $timestamps = false;

    protected $table = 'subscription_plans_features';

    protected $fillable = [
        'subscription_plan_id',
        'key',
        'value',
        'description',
        'limit',
        'position',
    ];
}

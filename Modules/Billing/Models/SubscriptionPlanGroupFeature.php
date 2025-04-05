<?php

namespace Modules\Billing\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlanGroupFeature extends Model
{
    public $timestamps = false;

    protected $table = 'subscription_plans_groups_features';

    protected $fillable = [
        'name',
        'sort',
    ];

}

<?php

namespace Modules\Billing\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlanFeature extends Model
{
    use FiresModelLifecycleEvents;

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

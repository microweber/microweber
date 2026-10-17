<?php

namespace Modules\Billing\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlanGroupFeature extends Model
{
    use FiresModelLifecycleEvents;

    public $timestamps = false;

    protected $table = 'subscription_plans_groups_features';

    protected $fillable = [
        'name',
        'sort',
    ];

}

<?php

namespace Modules\Billing\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlanGroupFeature extends Model
{

    /**
     * Fire lifecycle events so other modules can hook this model's changes.
     */
    protected $dispatchesEvents = [
        'created' => \Modules\Billing\Events\SubscriptionPlanGroupFeatureWasCreated::class,
        'updated' => \Modules\Billing\Events\SubscriptionPlanGroupFeatureWasUpdated::class,
        'deleted' => \Modules\Billing\Events\SubscriptionPlanGroupFeatureWasDeleted::class,
    ];
    use FiresModelLifecycleEvents;

    public $timestamps = false;

    protected $table = 'subscription_plans_groups_features';

    protected $fillable = [
        'name',
        'sort',
    ];

}

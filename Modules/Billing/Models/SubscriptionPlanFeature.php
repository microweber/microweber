<?php

namespace Modules\Billing\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlanFeature extends Model
{

    /**
     * Fire lifecycle events so other modules can hook this model's changes.
     */
    protected $dispatchesEvents = [
        'created' => \Modules\Billing\Events\SubscriptionPlanFeatureWasCreated::class,
        'updated' => \Modules\Billing\Events\SubscriptionPlanFeatureWasUpdated::class,
        'deleted' => \Modules\Billing\Events\SubscriptionPlanFeatureWasDeleted::class,
    ];
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

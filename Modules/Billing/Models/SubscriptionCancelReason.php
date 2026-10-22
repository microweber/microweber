<?php

namespace Modules\Billing\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionCancelReason extends Model
{

    /**
     * Fire lifecycle events so other modules can hook this model's changes.
     */
    protected $dispatchesEvents = [
        'created' => \Modules\Billing\Events\SubscriptionCancelReasonWasCreated::class,
        'updated' => \Modules\Billing\Events\SubscriptionCancelReasonWasUpdated::class,
        'deleted' => \Modules\Billing\Events\SubscriptionCancelReasonWasDeleted::class,
    ];
    use FiresModelLifecycleEvents;

    use HasFactory;

    protected $table = 'subscription_cancel_reasons';

    protected $fillable = [
        'user_id',
        'subscription_id',
        'stripe_session_id',
        'reason',
        'ip_address',
    ];
}

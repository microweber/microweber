<?php

namespace Modules\Order\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCancelReason extends Model
{

    /**
     * Fire lifecycle events so other modules can hook this model's changes.
     */
    protected $dispatchesEvents = [
        'created' => \Modules\Order\Events\OrderCancelReasonWasCreated::class,
        'updated' => \Modules\Order\Events\OrderCancelReasonWasUpdated::class,
        'deleted' => \Modules\Order\Events\OrderCancelReasonWasDeleted::class,
    ];
    use FiresModelLifecycleEvents;

    use HasFactory;

    protected $table = 'order_cancel_reasons';

    protected $fillable = [
        'user_id',
        'order_id',
        'stripe_session_id',
        'reason',
        'ip_address',
    ];
}

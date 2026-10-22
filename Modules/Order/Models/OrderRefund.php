<?php

namespace Modules\Order\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Payment\Models\Payment;

class OrderRefund extends Model
{

    /**
     * Fire lifecycle events so other modules can hook this model's changes.
     */
    protected $dispatchesEvents = [
        'created' => \Modules\Order\Events\OrderRefundWasCreated::class,
        'updated' => \Modules\Order\Events\OrderRefundWasUpdated::class,
        'deleted' => \Modules\Order\Events\OrderRefundWasDeleted::class,
    ];
    use FiresModelLifecycleEvents;

    protected $table = 'order_refunds';

    protected $fillable = [
        'order_id',
        'payment_id',
        'amount',
        'type',
        'reason',
        'note',
        'status',
        'refunded_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function refundedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'refunded_by');
    }
}

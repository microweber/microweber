<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Payment\Models\Payment;

class OrderRefund extends Model
{
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

<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCancelReason extends Model
{
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

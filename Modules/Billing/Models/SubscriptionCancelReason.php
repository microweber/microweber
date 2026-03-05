<?php

namespace Modules\Billing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionCancelReason extends Model
{
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

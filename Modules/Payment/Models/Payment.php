<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Payment\Enums\PaymentStatus;
use Modules\Payment\Events\PaymentWasCreated;
use Modules\Payment\Events\PaymentWasDeleted;
use Modules\Payment\Events\PaymentWasUpdated;

class Payment extends Model
{
    protected $fillable = [
        'rel_id',
        'rel_type',
        'amount',
        'currency',
        'status',
        'payment_provider',
        'transaction_id',
        'payment_provider_id',
        'payment_data',
    ];

    protected $casts = [
    //    'status' => PaymentStatus::class,
        'payment_data' => 'array',
    ];


    protected $dispatchesEvents = [
        'created' => PaymentWasCreated::class,
        'updated' => PaymentWasUpdated::class,
        'deleted' => PaymentWasDeleted::class,
    ];
    public function paymentProvider()
    {

        return $this->belongsTo(PaymentProvider::class, 'payment_provider_id');
    }

}

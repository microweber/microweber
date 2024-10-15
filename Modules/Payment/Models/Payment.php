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
        'payment_provider_id',
        'payment_provider_reference_id',
        'amount',
        'currency',
        'status',
        'payment_data',
    ];


    protected $casts = [
        'status' => PaymentStatus::class,
        'payment_data' => 'array',
    ];


    protected $dispatchesEvents = [
        'created' => PaymentWasCreated::class,
        'updated' => PaymentWasUpdated::class,
        'deleted' => PaymentWasDeleted::class,
    ];


    public function getPaymentProviderNameAttribute()
    {
        return $this->paymentProvider->name;
    }

    public function paymentProvider()
    {
        return $this->belongsTo(PaymentProvider::class);
    }
}

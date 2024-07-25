<?php

namespace MicroweberPackages\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Payment\Enums\PaymentStatus;

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
    ];


    protected $casts = [
        'status' => PaymentStatus::class,
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

<?php

namespace MicroweberPackages\Payment\Models;

class PaymentProvider extends \Illuminate\Database\Eloquent\Model
{
    protected $fillable = [
        'id',
        'name',
        'provider',

        'is_active',
        'is_default',
        'settings',
        'position',
    ];

    protected $casts = [
        'settings' => 'array',
    ];
}

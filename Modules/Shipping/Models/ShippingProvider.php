<?php

namespace Modules\Shipping\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingProvider extends Model
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

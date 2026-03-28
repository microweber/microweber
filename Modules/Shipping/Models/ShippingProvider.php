<?php

namespace Modules\Shipping\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Shipping\Database\Factories\ShippingProviderFactory;

class ShippingProvider extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return ShippingProviderFactory::new();
    }

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

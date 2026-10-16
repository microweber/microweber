<?php

namespace Modules\Shipping\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Shipping\Database\Factories\ShippingProviderFactory;

class ShippingProvider extends Model
{
    // Lifecycle events so other modules can react to ShippingProvider changes.
    protected $dispatchesEvents = [
        'created' => \Modules\Shipping\Events\ShippingProviderWasCreated::class,
        'updated' => \Modules\Shipping\Events\ShippingProviderWasUpdated::class,
        'deleted' => \Modules\Shipping\Events\ShippingProviderWasDeleted::class,
    ];

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

<?php

namespace Modules\Payment\Models;

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


    public function getLogoAttribute()
    {
        if ($this->provider) {
            $provider = app()->payment_method_manager->driver($this->provider);
            if (method_exists($provider, 'logo')) {
                return $provider->logo();
            }
        }

    }

    public function logo(){
        return $this->getLogoAttribute();
    }


}

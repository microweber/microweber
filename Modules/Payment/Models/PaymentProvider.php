<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

class PaymentProvider extends \Illuminate\Database\Eloquent\Model
{
    use CacheableQueryBuilderTrait;
    use HasFactory;

    protected static function newFactory()
    {
        return \Modules\Payment\Database\Factories\PaymentProviderFactory::new();
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


    public function getLogoAttribute()
    {
        if ($this->provider) {
            try {
                $manager = app()->payment_method_manager;
                if (!$manager->driverExists($this->provider)) {
                    return null;
                }
                $provider = $manager->driver($this->provider);
                if (method_exists($provider, 'logo')) {
                    return $provider->logo();
                }
            } catch (\Exception $e) {
                return null;
            }
        }

    }

    public function logo(){
        return $this->getLogoAttribute();
    }


}

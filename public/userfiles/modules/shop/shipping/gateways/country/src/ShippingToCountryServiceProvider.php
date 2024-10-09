<?php


namespace MicroweberPackages\Shop\Shipping\Gateways\Country;

use Illuminate\Support\ServiceProvider;


class ShippingToCountryServiceProvider extends ServiceProvider
{
    public function register()
    {
         app()->resolving(\Modules\Shipping\ShippingManager::class, function (\Modules\Shipping\ShippingManager $shippingManager) {
            $shippingManager->extend('shop/shipping/gateways/country', function () {
                return new ShippingToCountry();
            });
        });
    }
}

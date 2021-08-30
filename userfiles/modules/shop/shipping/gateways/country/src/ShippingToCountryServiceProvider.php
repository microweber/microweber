<?php


namespace MicroweberPackages\Shop\Shipping\Gateways\Country;

use Illuminate\Support\ServiceProvider;


class ShippingToCountryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        app()->resolving(\MicroweberPackages\Shipping\ShippingManager::class, function (\MicroweberPackages\Shipping\ShippingManager $shippingManager) {
            $shippingManager->extend('shop/shipping/gateways/country', function () {
                return new ShippingToCountry();
            });
        });
    }
}

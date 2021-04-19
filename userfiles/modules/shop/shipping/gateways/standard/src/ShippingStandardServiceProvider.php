<?php


namespace MicroweberPackages\Shop\Shipping\Gateways\Standard;

use Illuminate\Support\ServiceProvider;


class ShippingStandardServiceProvider extends ServiceProvider
{
    public function boot()
    {
        app()->resolving(\MicroweberPackages\Shipping\ShippingManager::class, function (\MicroweberPackages\Shipping\ShippingManager $shippingManager) {
            $shippingManager->extend('shop/shipping/gateways/standard', function () {
                return new ShippingStandard();
            });
        });
    }
}
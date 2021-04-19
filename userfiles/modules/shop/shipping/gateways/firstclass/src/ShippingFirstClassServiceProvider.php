<?php


namespace MicroweberPackages\Shop\Shipping\Gateways\FirstClass;

use Illuminate\Support\ServiceProvider;


class ShippingFirstClassServiceProvider extends ServiceProvider
{
    public function boot()
    {
        app()->resolving(\MicroweberPackages\Shipping\ShippingManager::class, function (\MicroweberPackages\Shipping\ShippingManager $shippingManager) {
            $shippingManager->extend('shop/shipping/gateways/firstclass', function () {
                return new ShippingFirstClass();
            });
        });
    }
}
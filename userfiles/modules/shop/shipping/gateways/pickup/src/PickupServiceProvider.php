<?php


namespace MicroweberPackages\Shop\Shipping\Gateways\Pickup;

use Illuminate\Support\ServiceProvider;


class PickupServiceProvider extends ServiceProvider
{
    public function boot()
    {
        app()->resolving(\MicroweberPackages\Shipping\ShippingManager::class, function (\MicroweberPackages\Shipping\ShippingManager $shippingManager) {
            $shippingManager->extend('shop/shipping/gateways/pickup', function () {
                return new PickupDriver();
            });
        });
    }
}
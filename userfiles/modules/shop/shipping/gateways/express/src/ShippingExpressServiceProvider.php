<?php


namespace MicroweberPackages\Shop\Shipping\Gateways\Express;

use Illuminate\Support\ServiceProvider;


class ShippingExpressServiceProvider extends ServiceProvider
{
    public function boot()
    {
        app()->resolving(\MicroweberPackages\Shipping\ShippingManager::class, function (\MicroweberPackages\Shipping\ShippingManager $shippingManager) {
            $shippingManager->extend('shop/shipping/gateways/express', function () {
                return new ShippingExpress();
            });
        });
    }
}
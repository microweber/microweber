<?php


namespace MicroweberPackages\Shop\Shipping\Gateways\Collection;

use Illuminate\Support\ServiceProvider;


class CollectionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        app()->resolving(\MicroweberPackages\Shipping\ShippingManager::class, function (\MicroweberPackages\Shipping\ShippingManager $shippingManager) {
            $shippingManager->extend('shop/shipping/gateways/collection', function () {
                return new CollectionDriver();
            });
        });
    }
}
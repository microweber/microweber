<?php


app()->resolving(\MicroweberPackages\Shipping\ShippingManager::class, function (\MicroweberPackages\Shipping\ShippingManager $shippingManager, $app) {

    $shippingManager->extend('country',function (){
        return  new \shop\shipping\gateways\country\ShippingToCountryDriver();
    });

});
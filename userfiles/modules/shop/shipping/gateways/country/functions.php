<?php


autoload_add_namespace(__DIR__.'/src/', 'MicroweberPackages\\Shop\\Shipping\\Gateways\\Country\\');



//app()->resolving(\MicroweberPackages\Shipping\ShippingManager::class, function (\MicroweberPackages\Shipping\ShippingManager $shippingManager, $app) {
//
//    $shippingManager->extend('country',function (){
//        return  new \shop\shipping\gateways\country\ShippingToCountryProvider();
//    });
//
//});
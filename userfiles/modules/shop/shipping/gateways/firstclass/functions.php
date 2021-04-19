<?php


autoload_add_namespace(__DIR__.'/src/', 'MicroweberPackages\\Shop\\Shipping\\Gateways\\FirstClass\\');



//app()->resolving(\MicroweberPackages\Shipping\ShippingManager::class, function (\MicroweberPackages\Shipping\ShippingManager $shippingManager, $app) {
//
//    $shippingManager->extend('firstclass',function (){
//        return  new \shop\shipping\gateways\firstclass\ShippingFirstClassProvider();
//    });
//
//});
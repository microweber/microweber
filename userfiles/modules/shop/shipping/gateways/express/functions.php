<?php


autoload_add_namespace(__DIR__.'/src/', 'MicroweberPackages\\Shop\\Shipping\\Gateways\\Express\\');



//app()->resolving(\MicroweberPackages\Shipping\ShippingManager::class, function (\MicroweberPackages\Shipping\ShippingManager $shippingManager, $app) {
//
//    $shippingManager->extend('express',function (){
//        return  new \shop\shipping\gateways\express\ShippingExpressProvider();
//    });
//
//});
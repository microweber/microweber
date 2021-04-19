<?php


autoload_add_namespace(__DIR__.'/src/', 'MicroweberPackages\\Shop\\Shipping\\Gateways\\Standard\\');



//app()->resolving(\MicroweberPackages\Shipping\ShippingManager::class, function (\MicroweberPackages\Shipping\ShippingManager $shippingManager, $app) {
//
//    $shippingManager->extend('standard',function (){
//        return  new \shop\shipping\gateways\standard\ShippingStandardProvider();
//    });
//
//});
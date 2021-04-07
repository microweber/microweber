<?php

$config = array();
$config['name'] = "Shipping to address";
$config['author'] = "Microweber";
$config['ui'] = false;
$config['ui_admin'] = false;
$config['categories'] = "online shop";
$config['position'] = 100;
$config['type'] = "shipping_gateway";
$config['version'] = "0.2";
$config['settings']['icon_class'] = "mdi mdi-walk";

$config['settings']['service_provider'] = [
    \MicroweberPackages\Shop\Shipping\Gateways\Country\ShippingToCountryEventServiceProvider::class,
    \MicroweberPackages\Shop\Shipping\Gateways\Country\ShippingToCountryServiceProvider::class,
];

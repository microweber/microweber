<?php

$config = array();
$config['name'] = "Shipping to address";
$config['author'] = "Microweber";
$config['ui'] = false;
$config['ui_admin'] = false;
$config['categories'] = "online shop";
$config['position'] = 100;
$config['type'] = "shipping_gateway";
$config['version'] = "0.3";
$config['settings']['checkout_position'] = 1;
$config['settings']['icon_class'] = "mdi mdi-truck-check-outline";
$config['settings']['help_text'] = "The order will be delivered to your address";


$config['settings']['service_provider'] = [
    \MicroweberPackages\Shop\Shipping\Gateways\Country\ShippingToCountryEventServiceProvider::class,
    \MicroweberPackages\Shop\Shipping\Gateways\Country\ShippingToCountryServiceProvider::class,
];

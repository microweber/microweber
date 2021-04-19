<?php

$config = array();
$config['name'] = "First Class";
$config['author'] = "Ezyweb";
$config['ui'] = false;
$config['ui_admin'] = false;
$config['categories'] = "online shop";
$config['position'] = 100;
$config['type'] = "shipping_gateway";
$config['version'] = "0.2";
$config['settings']['checkout_position'] = 2;
$config['settings']['icon_class'] = "mdi mdi-truck-check-outline";
$config['settings']['help_text'] = "The order will be delivered to your address by First Class Mail";


$config['settings']['service_provider'] = [
    \MicroweberPackages\Shop\Shipping\Gateways\FirstClass\ShippingFirstClassEventServiceProvider::class,
    \MicroweberPackages\Shop\Shipping\Gateways\FirstClass\ShippingFirstClassServiceProvider::class,
];

<?php

$config = array();
$config['name'] = "Click & Collect";
$config['author'] = "Microweber";
$config['ui'] = false;
$config['ui_admin'] = false;
$config['categories'] = "online shop";
$config['position'] = 900;
$config['type'] = "shipping_gateway";
$config['version'] = "0.2";
$config['settings']['checkout_position'] = 0;
$config['settings']['icon_class'] = "mdi mdi-walk";
$config['settings']['help_text'] = "Collect your order from our store";

$config['settings']['service_provider'] = [
    \MicroweberPackages\Shop\Shipping\Gateways\Collection\CollectionEventServiceProvider::class,
    \MicroweberPackages\Shop\Shipping\Gateways\Collection\CollectionServiceProvider::class,
];

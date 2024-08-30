<?php

$config = array();
$config['name'] = "Paypal Express";
$config['author'] = "Microweber";
$config['ui'] = false;
$config['categories'] = "online shop";
$config['position'] = 110;
$config['type'] = "payment_gateway";



$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Payment\\Providers\\Paypal\\'
    ],
];
$config['settings']['service_provider'] = [
    \MicroweberPackages\Payment\Providers\Paypal\PaypalServiceProvider::class,
];

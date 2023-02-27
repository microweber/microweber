<?php

$config = array();
$config['name'] = "Stripe payment";
$config['author'] = "Microweber";
$config['ui'] = false;
$config['categories'] = "online shop";
$config['position'] = 132;
$config['type'] = "payment_gateway";


$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Payment\\Providers\\Stripe\\'
    ],
];
$config['settings']['service_provider'] = [
    \MicroweberPackages\Payment\Providers\Stripe\StripeServiceProvider::class,
];

<?php

$config = array();
$config['name'] = "Mollie payment";
$config['author'] = "Microweber";
$config['ui'] = false;
$config['categories'] = "online shop";
$config['position'] = 136;
$config['type'] = "payment_gateway";

$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Payment\\Providers\\Mollie\\'
    ],
];
$config['settings']['service_provider'] = [
    \MicroweberPackages\Payment\Providers\Mollie\MollieServiceProvider::class,
];

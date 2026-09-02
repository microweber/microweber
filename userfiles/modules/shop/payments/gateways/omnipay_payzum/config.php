<?php

$config = array();
$config['name'] = "Payzum payment";
$config['author'] = "Payzum";
$config['ui'] = false;
$config['categories'] = "online shop";
$config['position'] = 137;
$config['type'] = "payment_gateway";

$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Payment\\Providers\\Payzum\\'
    ],
];
$config['settings']['service_provider'] = [
    \MicroweberPackages\Payment\Providers\Payzum\PayzumServiceProvider::class,
];

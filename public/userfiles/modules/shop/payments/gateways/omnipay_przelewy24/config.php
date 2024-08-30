<?php

$config = array();
$config['name'] = "Przelewy24";
$config['author'] = "Microweber";
$config['ui'] = false;
$config['categories'] = "online shop";
$config['position'] = 136;
$config['type'] = "payment_gateway";



$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Payment\\Providers\\Przelewy24\\'
    ],
];
$config['settings']['service_provider'] = [
    \MicroweberPackages\Payment\Providers\Przelewy24\Przelewy24ServiceProvider::class,
];

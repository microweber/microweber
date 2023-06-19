<?php

$config = array();
$config['name'] = "Google Maps";
$config['author'] = "Microweber";
$config['no_cache'] = false;
$config['ui'] = true;
$config['categories'] = "essentials";
$config['version'] = "0.6";
$config['position'] = 19;


$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Modules\\GoogleMaps\\'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\GoogleMaps\Providers\GoogleMapsServiceProvider::class
];


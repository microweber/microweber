<?php

$config = array();
$config['name'] = "Background Image";
$config['author'] = "Microweber";
$config['description'] = "Microweber";
$config['website'] = "http://microweber.com/";
$config['help'] = "http://microweber.com/modules/background";
$config['version'] = 1.2;
$config['ui'] = false;
$config['position'] = 333;
$config['categories'] = "media";

$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Modules\\Background'
    ],
];
$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Background\Providers\BackgroundImageServiceProvider::class
];

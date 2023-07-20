<?php

$config = array();
$config['name'] = "Examples of Microweber UI";
$config['author'] = "Microweber";
$config['no_cache'] = false;
$config['ui'] = true;
$config['categories'] = "miscellaneous";
$config['position'] = 38;
$config['version'] = "0.1";

$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Modules\\ExampleUi'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\ExampleUi\Providers\LogoServiceProvider::class
];

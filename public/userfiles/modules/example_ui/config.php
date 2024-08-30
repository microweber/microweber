<?php

$config = array();
$config['name'] = "Examples of Microweber UI";
$config['author'] = "Microweber";
$config['no_cache'] = false;
$config['ui'] = false;
$config['ui_admin'] = false;
$config['categories'] = "miscellaneous";
$config['position'] = 999;
$config['version'] = "0.2";

$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Modules\\ExampleUi'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\ExampleUi\Providers\ExampleUiServiceProvider::class
];

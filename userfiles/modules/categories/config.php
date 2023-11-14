<?php

$config = array();
$config['name'] = "Categories";
$config['author'] = "Microweber";
$config['no_cache'] = false;
$config['ui'] = true;
$config['position'] = 29;
$config['categories'] = "navigation";
$config['version'] = 0.1;
$config['is_system'] = true;



$config['settings'] = [];
$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\Modules\Categories'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Categories\Providers\CategoryServiceProvider::class
];

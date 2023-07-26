<?php
$config = array();
$config['name'] = "Sharer";
$config['author'] = "Microweber";
$config['no_cache'] = false;
$config['ui'] = true;
$config['categories'] = "social";
$config['position'] = 210;
$config['version'] = 1.3;

$config['settings'] = [];
$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\Modules\Sharer'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Sharer\Providers\SharerServiceProvider::class
];


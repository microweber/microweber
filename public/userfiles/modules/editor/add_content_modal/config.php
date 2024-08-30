<?php

$config = array();
$config['name'] = "Microweber - Add content modal btn";
$config['author'] = "Microweber";
$config['no_cache'] = false;
$config['ui'] = false;
$config['ui_admin'] = false;
$config['categories'] = "miscellaneous";
$config['position'] = 38;
$config['version'] = "0.1";


$config['settings'] = [];
$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\Modules\Editor\AddContentModal'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Editor\AddContentModal\Providers\AddContentModalServiceProvider::class
];


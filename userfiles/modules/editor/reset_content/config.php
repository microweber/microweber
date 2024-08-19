<?php

$config = array();
$config['name'] = "Reset content";
$config['ui'] = false;
$config['ui_admin'] = false;
$config['categories'] = "other";
$config['position'] = "280";
$config['version'] = "0.3";


$config['settings'] = [];
$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\Modules\Editor\ResetContent'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Editor\ResetContent\Providers\ResetContentModuleServiceProvider::class
];

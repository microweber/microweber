<?php

$config = array();
$config['name'] = "Settings";
$config['author'] = "Microweber";
$config['ui_admin'] = false;
$config['ui'] = false;
$config['is_system'] = true;
$config['categories'] = "admin";
$config['position'] = 4;
$config['version'] = 0.4;

$config['settings'] = [];
$config['settings']['routes'] = [
    'admin'=>'admin.settings.index'
];

$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Modules\\Settings'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Settings\Providers\SettingsServiceProvider::class
];


<?php

$config = array();
$config['name'] = "Microweber - Editor Fonts";
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
        'namespace' => 'MicroweberPackages\Modules\Editor\Fonts\FontsSettings'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Editor\Fonts\FontsSettings\Providers\FontsSettingsSettingsServiceProvider::class
];


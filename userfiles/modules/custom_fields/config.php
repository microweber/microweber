<?php

$config = array();
$config['name'] = "Custom fields";
$config['author'] = "Microweber";
$config['no_cache'] = false;
$config['ui'] = false;
$config['ui_admin'] = false;
$config['position'] = 15;
$config['is_system'] = true;




$config['settings'] = [];
$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\Modules\CustomFields'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\CustomFields\Providers\CustomFieldsServiceProvider::class
];

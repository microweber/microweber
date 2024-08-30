<?php
$config = array();
$config['name'] ="Tabs";
$config['author'] = "Microweber";
$config['ui'] = true;
$config['version'] = 0.01;
$config['categories'] = "miscellaneous";
$config['position'] = 52;

$config['settings'] = [];
$config['settings']['translatable_options'] = [
    'settings'
];
$config['settings']['allowed_html_option_keys'] = [
    'settings',
];

$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\Modules\Tabs'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Tabs\Providers\TabsServiceProvider::class
];

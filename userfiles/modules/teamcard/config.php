<?php
$config = array();
$config['name'] = "Team Card";
$config['author'] = "Microweber";
$config['ui'] = true;
$config['version'] = "0.2";
$config['position'] = 57;
$config['categories'] = "miscellaneous";


$config['settings'] = [];
$config['settings']['translatable_options'] = [
    'settings'
];
$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Modules\\Teamcard'
    ],
];
$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Teamcard\Providers\TeamcardServiceProvider::class
];

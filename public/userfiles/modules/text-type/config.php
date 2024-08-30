<?php
$config = array();
$config['name'] = "Text Type Animation";
$config['author'] = "Microweber";
$config['no_cache'] = false;
$config['ui'] = true;
$config['categories'] = "miscellaneous";
$config['position'] = 39;
$config['version'] = 1.0;

$config['settings'] = [];
$config['settings']['allowed_html_option_keys'] = [
    'text',
];

$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Modules\\TextType'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\TextType\Providers\TextTypeServiceProvider::class
];

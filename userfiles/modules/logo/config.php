<?php
$config = array();
$config['name'] = "Logo";
$config['author'] = "Microweber";
$config['no_cache'] = false;
$config['ui'] = true;
$config['categories'] = "miscellaneous";
$config['position'] = 39;
$config['version'] = 1.1;

$config['settings'] = [];
$config['settings']['translatable_options'] = [
    'text',
    'font_family',
    'logotype',
    'logoimage',
    'size',
    'settings'
];

$config['settings']['allowed_html_option_keys'] = [
    'text',
];


$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Modules\\Logo'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Logo\Providers\LogoServiceProvider::class
];

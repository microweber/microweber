<?php

$config = array();
$config['name'] = "Social Links";
$config['author'] = "Microweber";
$config['no_cache'] = false;
$config['ui'] = true;
$config['categories'] = "social";
$config['position'] = 9;
$config['version'] = 1;



$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Modules\\SocialLinks'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\SocialLinks\Providers\SocialLinksServiceProvider::class
];

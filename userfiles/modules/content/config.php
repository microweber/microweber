<?php

$config = array();
$config['name'] = "Content";
$config['description'] ="Shows dynamic content";
$config['author'] = "Microweber";
$config['categories'] = "essentials";
$config['version'] = 0.1;
$config['position'] = 22;
$config['ui'] = true;
$config['ui_admin'] = false;
$config['is_system'] = false;


$config['settings'] = [];
$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\Modules\Content'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Content\Providers\ContentServiceProvider::class
];


<?php

$config = array();
$config['name'] = "Menu";
$config['description'] = "Navigation menu for pages and links.";
$config['author'] = "Microweber";
$config['ui'] = true;
$config['ui_admin'] = true;
$config['categories'] = "essentials";
$config['position'] = 27;
$config['version'] = 0.6;



$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Modules\\Menu'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Menu\Providers\MenuModuleServiceProvider::class
];


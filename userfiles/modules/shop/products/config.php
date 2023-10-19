<?php

$config = array();
$config['name'] = "Products";
$config['author'] = "Microweber";
$config['no_cache'] = false;
$config['ui'] = true;
$config['ui_admin'] = true;
$config['categories'] = "store";
$config['version'] = 0.41;
$config['position'] = 21;


$config['settings'] = [];
$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\Modules\Shop\Products'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Shop\Products\Providers\ProductsServiceProvider::class
];


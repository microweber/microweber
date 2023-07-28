<?php
$config = array();
$config['name'] = "PDF";
$config['author'] = "Microweber";
$config['no_cache'] = false;
$config['ui'] = true;
$config['categories'] = "miscellaneous";
$config['position'] = 40;
$config['version'] = 1;


$config['settings'] = [];
$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\Modules\PDF'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\PDF\Providers\PDFServiceProvider::class
];


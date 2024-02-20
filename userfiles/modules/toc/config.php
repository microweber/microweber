<?php
$config = array();
$config['name'] = "TOC";
$config['author'] = "Microweber";
$config['no_cache'] = false;
$config['ui'] = true;
$config['ui_admin'] = false;
$config['categories'] = "content";
$config['position'] = 39;
$config['version'] = 1.0;

$config['settings'] = [];
$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Modules\\Toc'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Toc\Providers\TocServiceProvider::class
];

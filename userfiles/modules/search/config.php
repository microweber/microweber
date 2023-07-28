<?php

$config = array();
$config['name'] = "Search";
$config['author'] = "Microweber";
$config['description'] = "Module to search for content";
$config['website'] = "http://microweber.com/";
$config['help'] = "http://microweber.info/modules/search";
$config['version'] = 0.2;
$config['ui'] = true;
$config['position'] = 34;
$config['categories'] = "miscellaneous";



$config['settings'] = [];
$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\Modules\Search'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Search\Providers\SearchServiceProvider::class
];


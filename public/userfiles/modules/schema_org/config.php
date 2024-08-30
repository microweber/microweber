<?php

$config = array();
$config['name'] = "Schema.org";
$config['author'] = "Bozhidar Slaveykov";
$config['description'] = "Microweber";
$config['website'] = "http://microweber.com/";
$config['version'] = 1;
$config['ui'] = false;
$config['ui_admin'] = false;
$config['position'] = 100;
$config['categories'] = "miscellaneous";

$config['settings'] = [];
$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\Modules\SchemaOrg'
    ],
];

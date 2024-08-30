<?php

$config = array();
$config['name'] = "Posts List";
$config['author'] = "Microweber";
$config['no_cache'] = false;
$config['ui'] = true;
$config['categories'] = "essentials";
$config['version'] = 0.3;
$config['position'] = 20;
$config['is_system'] = true;
$config['options'] = array();
$config['options']['zoom'] = array("type"=>"number", "default"=> 11);
$config['options']['category'] = array("type"=>"category_tree", "default"=> '');


$config['settings'] = [];
$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\Modules\Posts'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Posts\Providers\PostsServiceProvider::class
];



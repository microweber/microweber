<?php

$config = array();
$config['name'] = "Audio";
$config['author'] = "Microweber";
$config['description'] = "Microweber";
$config['website'] = "http://microweber.com/";
$config['help'] = "http://microweber.info/modules/audio";
$config['version'] = 0.19;
$config['ui'] = true;
$config['position'] = 30;
$config['categories'] = "media";

$config['settings'] = [];
$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\Modules\Audio'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Audio\Providers\AudioServiceProvider::class
];


<?php

$config = array();
$config['name'] = "Facebook page";
$config['link'] = "https://microweber.com";
$config['description'] = "Facebook page integration for your website!";
$config['author'] = "";
$config['author_website'] = "";
$config['ui'] = true;
$config['ui_admin'] = false;
$config['categories'] = "social";
$config['position'] = 11;
$config['version'] = 0.01;

$config['settings'] = [];
$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\Modules\FacebookPage'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\FacebookPage\Providers\FacebookPageServiceProvider::class
];

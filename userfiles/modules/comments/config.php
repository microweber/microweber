<?php
$config = array();
$config['name'] = "Comments";
$config['author'] = "Microweber";
$config['ui_admin'] = true;
$config['ui_admin_iframe'] = true;
$config['ui'] = true;
$config['categories'] = "content";
$config['position'] = 200;
$config['version'] = 1.0;
$config['settings'] = [];
$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Modules\\Comments'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Comments\CommentsServiceProvider::class
];

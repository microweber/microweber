<?php

$config = array();
$config['name'] = "Tweet Embed";
$config['author'] = "Microweber";
$config['no_cache'] = false;
$config['ui'] = true;
$config['categories'] = "social";
$config['position'] = 200;
$config['version'] = 1.1;


$config['settings'] = [];
$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\Modules\TweetEmbed'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\TweetEmbed\Providers\TweetEmbedServiceProvider::class
];


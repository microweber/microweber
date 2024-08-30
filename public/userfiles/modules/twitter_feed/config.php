<?php

$config = array();
$config['name'] = "Twitter feed";
$config['link'] = "https://microweber.com";
$config['description'] = "Feed of tweets";
$config['author'] = "Peter Ivanov";
$config['author_website'] = "https://microweber.com";
$config['ui'] = true;
$config['categories'] = "social";
$config['position'] = 200;
$config['version'] = 0.4;

$config['settings'] = [];
$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\Modules\TwitterFeed'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\TwitterFeed\Providers\TwitterFeedServiceProvider::class
];


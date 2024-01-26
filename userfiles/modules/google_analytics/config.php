<?php
$config = array();
$config['name'] = "Google Analytics";
$config['author'] = "Microweber";
$config['ui_admin'] = true;
$config['ui_admin_iframe'] = true;
$config['ui'] = true;
$config['categories'] = "content";
$config['position'] = 200;
$config['version'] = 1.2;
$config['settings'] = [];

// Here is the index route for admin panel
$config['settings']['routes']['admin'] = 'admin.google_analytics.index';

$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Modules\\GoogleAnalytics'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\GoogleAnalytics\Providers\GoogleAnalyticsServiceProvider::class
];


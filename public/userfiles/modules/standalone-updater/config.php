<?php
$config = array();
$config['name'] = "Standalone Updater";
$config['author'] = "bobi@microweber.com";
$config['no_cache'] = false;
$config['ui'] = false;
$config['ui_admin'] = true;
$config['is_system'] = true;
$config['categories'] = "other";
$config['position'] = 1;
$config['version'] = '5.3.8';



$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Modules\\StandaloneUpdater'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\StandaloneUpdater\StandaloneUpdaterServiceProvider::class
];

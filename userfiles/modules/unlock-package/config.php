<?php
$config = array();
$config['name'] = "Unlock Package";
$config['author'] = "Microweber";
$config['no_cache'] = false;
$config['ui'] = true;
$config['ui_admin'] = false;
$config['is_system'] = false;
$config['position'] = 0;
$config['version'] = "0.1";
$config['categories'] = "essentials";

$config['settings'] = [];
$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\Modules\UnlockPackage'
    ],
];

$config['settings']['service_provider'] = [
   \MicroweberPackages\Modules\UnlockPackage\Providers\UnlockPackageServiceProvider::class
];


<?php

$config = array();
$config['name'] = "Code editor";
$config['ui'] = false;
$config['ui_admin'] = false;
$config['categories'] = "other";
$config['position'] = "280";
$config['version'] = "0.2";


$config['settings'] = [];
$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\Modules\Editor\CodeEditor'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Editor\CodeEditor\Providers\CodeEditorModuleServiceProvider::class
];

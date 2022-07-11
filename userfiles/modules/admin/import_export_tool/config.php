<?php
$config = array();
$config['name'] = "Import Export Tool";
$config['author'] = "Microweber";

$config['categories'] = "admin";
$config['version'] = 0.3;
$config['ui_admin'] = false;
$config['ui'] = false;
$config['position'] = 99;

$config['settings'] = [];
$config['settings']['routes'] = [
    'admin'=>'admin.import-export-tool.index'
];

$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Modules\\Admin\\ImportExportTool\\'
    ],
];
$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Admin\ImportExportTool\ImportExportToolServiceProvider::class
];

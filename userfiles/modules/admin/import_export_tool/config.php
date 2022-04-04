<?php
$config = array();
$config['name'] = "Import\Export Tool";
$config['author'] = "Microweber";

$config['categories'] = "admin";
$config['version'] = 0.3;
$config['ui_admin'] = true;
$config['ui'] = true;
$config['position'] = 99;

$config['settings'] = [];
$config['settings']['routes'] = [
    'admin'=>'admin.import_export.index'
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\ImportExportTool\ImportExportToolServiceProvider::class
];

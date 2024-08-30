<?php
$config = array();
$config['name'] = "Editor Template Settings";
$config['author'] = "Microweber";
$config['ui'] = false;
$config['ui_admin'] = false;
$config['is_system'] = false;
$config['position'] = 200;
$config['version'] = 0.1;

$config['settings'] = [];

$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Editor\\TemplateSettingsV2\\'
    ],
];
$config['settings']['service_provider'] = [
    MicroweberPackages\Editor\TemplateSettingsV2\Providers\EditorTemplateSettingsV2ServiceProvider::class
];

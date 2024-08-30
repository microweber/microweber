<?php
$config = array();
$config['name'] = "Layouts";
$config['author'] = "Microweber";
$config['ui'] = false; //if set to true, module will be visible in the toolbar
$config['ui_admin'] = false; //if set to true, module will be visible in the admin panel
$config['categories'] = "content";
$config['position'] = 99;
$config['version'] = 0.1;

$config['tables'] = array();

//$config['settings'][]  = array(
//    'type'=>'tooltip',
//    'title'=>'Spacing',
//    'icon'=>'mw-icon-wand',
//    'view'=>'quick_settings',
//);

$config['settings']['translatable_options'] = [
    'title',
    'type',
    'icon',
    'view'
];


$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\Modules\Layouts'
    ],
];

$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Layouts\Providers\LayoutsModuleServiceProvider::class
];

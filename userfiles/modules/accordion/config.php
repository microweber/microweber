<?php
$config = array();
$config['name'] = "Accordion";
$config['author'] = "Microweber";
$config['ui'] = true;
$config['version'] = 0.01;
$config['categories'] = "miscellaneous";
$config['position'] = 52;

$config['settings'] = [];
$config['settings']['translatable_options'] = [
    'settings'
];


$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Modules\\Accordion'
    ],
];
$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\Accordion\Providers\AccordionServiceProvider::class
];




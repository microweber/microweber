<?php
$config = array();
$config['name'] = "Mail Templates";
$config['author'] = "Microweber";
//$config['ui'] = false;
$config['position'] = 100;
//$config['type'] = "mail_templates";



$config['settings'] = [];
$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\Modules\MailTemplates'
    ],
];
$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\MailTemplates\MailTemplatesServiceProvider::class
];

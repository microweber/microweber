<?php
$config = array();
$config['name'] = "Contact form";
$config['author'] = "Microweber";
$config['no_cache'] = false;
$config['ui'] = true;
$config['ui_admin'] = true;
$config['categories'] = "essentials";
$config['position'] = 15;
$config['version'] = 0.2;

$config['ui'] = true;
$config['ui_admin'] = true;
$config['is_system'] = true;
$config['is_integration'] = 1;


$config['settings'] = [];
$config['settings']['translatable_options'] = [
    'email_autorespond_subject',
    'email_autorespond'
];

// Here is the index route for admin panel
$config['settings']['routes'] = [
    'admin'=>'admin.contact-form.index'
];

// Make autoload of you module folder
$config['settings']['autoload_namespace'] = [
    [
        'path' => __DIR__ . '/src/',
        'namespace' => 'MicroweberPackages\\Modules\\ContactForm\\'
    ],
];

// Register service provider of you module
$config['settings']['service_provider'] = [
    \MicroweberPackages\Modules\ContactForm\ContactFormServiceProvider::class
];

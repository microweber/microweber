<?php
$config = array();
$config['name'] = "Contact form";
$config['author'] = "Microweber";
$config['no_cache'] = false;
$config['ui'] = true;
$config['ui_admin'] = true;
$config['categories'] = "forms";
$config['position'] = 15;
$config['version'] = 0.2;

$config['settings'] = [];
$config['settings']['translatable_options'] = [
    'email_autorespond_subject',
    'email_autorespond'
];

$config['ui'] = true;
$config['ui_admin'] = true;
$config['is_system'] = true;
$config['is_integration'] = 1;

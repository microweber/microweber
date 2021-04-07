<?php
$config = array();
$config['name'] = "Website Settings";
$config['author'] = "Microweber";
$config['ui_admin'] = false;
$config['ui'] = false;
$config['is_system'] = true;

$config['categories'] = "admin";
$config['position'] = 400;
$config['version'] = 0.3;


$config['settings'] = [];
$config['settings']['translatable_options'] = [
    'website_title',
    'website_description',
    'website_keywords',
];

<?php
$config = array();
$config['name'] = "Backup";
$config['author'] = "Microweber";

$config['categories'] = "admin";
$config['version'] = 2.0;
$config['ui_admin'] = true;
$config['position'] = 99;

$config['permissions'] = [
    'index' => 'module.admin.backup.index',
    'create' => 'module.admin.backup.create',
    'edit' => 'module.admin.backup.edit',
    'destroy' => 'module.admin.backup.destroy'
];

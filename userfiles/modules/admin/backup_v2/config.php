<?php
$config = array();
$config['name'] = "Backup V2";
$config['author'] = "Microweber";

$config['categories'] = "admin";
$config['version'] = 0.3;
$config['ui_admin'] = true;
$config['position'] = 99;

$config['permissions'] = [
    'index' => 'module.admin.backup_v2.index',
    'create' => 'module.admin.backup_v2.create',
    'edit' => 'module.admin.backup_v2.edit',
    'destroy' => 'module.admin.backup_v2.destroy'
];
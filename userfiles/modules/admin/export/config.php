<?php
$config = array();
$config['name'] = "Migration Tool";
$config['author'] = "Microweber";

$config['categories'] = "admin";
$config['version'] = 0.3;
$config['ui_admin'] = true;
$config['position'] = 99;

$config['permissions'] = [
    'index' => 'module.admin.migration_tool.index',
    'create' => 'module.admin.migration_tool.create',
    'edit' => 'module.admin.migration_tool.edit',
    'destroy' => 'module.admin.migration_tool.destroy'
];

<?php
$config = array();
$config['name'] = "Import Tool";
$config['author'] = "Microweber";

$config['categories'] = "admin";
$config['version'] = 0.3;
$config['ui_admin'] = false;
$config['ui'] = false;
$config['position'] = 99;

$config['permissions'] = [
    'index' => 'module.admin.import_tool.index',
    'create' => 'module.admin.import_tool.create',
    'edit' => 'module.admin.import_tool.edit',
    'destroy' => 'module.admin.import_tool.destroy'
];

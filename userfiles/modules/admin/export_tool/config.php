<?php
$config = array();
$config['name'] = "Export Tool";
$config['author'] = "Microweber";

$config['categories'] = "admin";
$config['version'] = 0.3;
$config['ui_admin'] = false;
$config['ui'] = false;
$config['position'] = 99;

$config['permissions'] = [
    'index' => 'module.admin.export_tool.index',
    'create' => 'module.admin.export_tool.create',
    'edit' => 'module.admin.export_tool.edit',
    'destroy' => 'module.admin.export_tool.destroy'
];

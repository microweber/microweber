<?php

$getContents = get_module_option('contents', $params['id']);
$contents = json_decode($getContents, true);

if (empty($contents)) {
    $newModuleDefaultSettingsApplied = new \MicroweberPackages\Module\ModuleDefaultSettingsApplier();
    $newModuleDefaultSettingsApplied->moduleName = 'layout_content';
    $newModuleDefaultSettingsApplied->modulePath = __DIR__;
    $applied = $newModuleDefaultSettingsApplied->apply();
    if (isset($applied['success']) && $applied['success']) {
        $getContents = get_module_option('contents', $params['id']);
        $contents = json_decode($getContents, true);
    }
}

$title = get_module_option('title', $params['id']);
$description = get_module_option('description', $params['id']);
$align = get_module_option('align', $params['id']);
if (!$align) {
    $align = 'center';
}
$maxColumns = get_module_option('maxColumns', $params['id']);
if (!$maxColumns) {
    $maxColumns = 3;
}

if(!$contents){
    $contents = array();
}

if (count($contents) == 0) {
    echo lnotif("Click on settings to edit this module");
}

$module_template = get_module_option('template', $params['id']);

if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}
if ($module_template != false) {
    $template_file = module_templates($config['module'], $module_template);
} else {
    $template_file = module_templates($config['module'], 'default');
}
if (is_file($template_file)) {
    include($template_file);
}

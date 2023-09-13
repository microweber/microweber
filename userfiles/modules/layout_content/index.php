<?php

$moduleName = 'layout_content';

$getContents = get_module_option('contents', $params['id']);
$contents = json_decode($getContents, true);

if (empty($contents)) {
    $isDefaultSettingsApplied = get_option('default_settings_is_applied', $params['id']);
    if (!$isDefaultSettingsApplied) {
        $defaultSettingsFile = dirname(__FILE__) . DS . 'default_settings.json';
        if (is_file($defaultSettingsFile)) {
            $checkForDefaultSettings = file_get_contents($defaultSettingsFile);
            if ($checkForDefaultSettings) {
                $defaultSettings = json_decode($checkForDefaultSettings, true);
                if (!empty($defaultSettings) && is_array($defaultSettings)) {

                    foreach ($defaultSettings as $defaultSettingOptionKey => $defaultSettingOptionValue) {
                        if (is_array($defaultSettingOptionValue)) {
                            $defaultSettingOptionValue = json_encode($defaultSettingOptionValue);
                        }
                        save_option([
                            'option_value' => $defaultSettingOptionValue,
                            'option_key' => $defaultSettingOptionKey,
                            'option_group' => $params['id'],
                            'module'=> $moduleName
                        ]);
                    }
                    save_option('default_settings_is_applied', true, $params['id']);

                    $getContents = get_module_option('contents', $params['id']);
                    $contents = json_decode($getContents, true);
                }
            }
        }
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

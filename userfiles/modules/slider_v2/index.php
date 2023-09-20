<?php
$settings = get_module_option('settings', $params['id']);
if (empty($settings)) {

    $newModuleDefaultSettingsApplied = new \MicroweberPackages\Module\ModuleDefaultSettingsApplier();
    $newModuleDefaultSettingsApplied->moduleName = 'slider_v2';
    $newModuleDefaultSettingsApplied->modulePath = __DIR__;
    $newModuleDefaultSettingsApplied->moduleId = $params['id'];

    $applied = $newModuleDefaultSettingsApplied->apply();

    if (isset($applied['success']) && $applied['success']) {
        $settings = get_module_option('settings', $params['id']);
    }

}

$moduleTemplate = get_module_option('template', $params['id']);
?>


new fresh slider

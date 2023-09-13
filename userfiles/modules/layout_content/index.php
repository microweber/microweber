<?php

$settings = get_module_option('settings', $params['id']);

$defaults = array(
    'title' => 'Title',
);
$is_empty = false;
$data = json_decode($settings, true);

if (empty($data)) {
    $isDefaultContentApplied = get_option('default_content_is_applied', $params['id']);
    if (!$isDefaultContentApplied) {
        $defaultContentFile = dirname(__FILE__) . DS . 'default_content.json';
        if (is_file($defaultContentFile)) {
            $checkForDefaultContent = file_get_contents($defaultContentFile);
            if ($checkForDefaultContent) {
                $defaultContentData = json_decode($checkForDefaultContent, true);
                if (!empty($defaultContentData) && is_array($defaultContentData)) {
                    $defaultContentReady = array();
                    $defaultContentDataI = 0;
                    foreach ($defaultContentData as $key => $value) {
                        $value['itemId'] = 'mw-module-'.$params['id'].'-'.$defaultContentDataI.time();
                        $defaultContentReady[$key] = $value;
                        $defaultContentDataI++;
                    }
                    save_option('settings', json_encode($defaultContentReady), $params['id']);
                    save_option('default_content_is_applied', true, $params['id']);
                    $settings = get_module_option('settings', $params['id']);
                    $data = json_decode($settings, true);
                }
            }
        }
    }
}

if(!$data){
    $data = array();
}

if (count($data) == 0) {
    $is_empty = true;
    print lnotif("Click on settings to edit this module");
    //  $data = array($defaults);
    //  return;
}

if(!empty($data)){
    //fill keys
    foreach ($data as $key => $value) {
        foreach ($defaults as $key2 => $value2) {
            if (!isset($data[$key][$key2])) {
                $data[$key][$key2] = $value2;
            }
        }
    }
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

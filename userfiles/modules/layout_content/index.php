<?php

$getContents = get_module_option('contents', $params['id']);
$contents = json_decode($getContents, true);

if (empty($contents)) {
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

                    save_option('contents', json_encode($defaultContentReady), $params['id']);
                    save_option('default_content_is_applied', true, $params['id']);
                    save_option('title', 'Your story online', $params['id']);
                    save_option('description', 'The way you tell your story online can make all the difference', $params['id']);

                    $getDefaultContents = get_module_option('contents', $params['id']);
                    $contents = json_decode($getDefaultContents, true);
                }
            }
        }
    }
}

$title = get_module_option('title', $params['id']);
$description = get_module_option('description', $params['id']);

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

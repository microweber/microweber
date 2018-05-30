<?php
$default_image = get_option('default-image', $params['id']);
$rollover_image = get_option('rollover-image', $params['id']);
$text = get_option('text', $params['id']);
$href_url = get_option('href-url', $params['id']);

if ($text == false or $text == '') {
    if (isset($params['text'])) {
        $text = $params['text'];
    }
}

$size = get_option('size', $params['id']);
if ($size == false or $size == '') {
    if (isset($params['size'])) {
        $size = $params['size'];
    } else {
        $size = 60;
    }

}

$module_template = get_option('data-template', $params['id']);
if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}

if ($module_template != false) {
    $template_file = module_templates($config['module'], $module_template);
} else {
    $template_file = module_templates($config['module'], 'default');
}

if (is_file($template_file) != false) {
    include($template_file);
} else {
    print lnotif("No template found. Please choose template.");
}

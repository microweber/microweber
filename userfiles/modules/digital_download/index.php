<?php


$button_text = get_option('button_text', $params['id']);
if (!$button_text) {
    $button_text = 'Download';
}

$button_alignment = get_option('button_alignment', $params['id']);
if (!$button_alignment) {
    $button_alignment = 'center';
}

$download_url = get_option('download_url', $params['id']);
if (!$download_url) {
    $download_url = '';
}

$require_email = get_option('require_email', $params['id']);
if (!$require_email) {
    $require_email = 'n';
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

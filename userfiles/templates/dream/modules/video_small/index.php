<?php


$upload = get_option('upload', $params['id']);

if ($upload == false) {
    $upload = template_url() . '/assets/video/video.mp4';
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
if (is_file($template_file)) {
    include($template_file);
}

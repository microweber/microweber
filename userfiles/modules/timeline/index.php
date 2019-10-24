<?php


$settings = get_option('settings', 'timeline');
//$settings = get_option('settings', $params['id']);

$defaults = array(
    'icon' => '',
    'date' => '',
    'description' => ''
);

$data = json_decode($settings, true);


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
if (!$data or count($data) == 0) {
    print lnotif("Click on settings to edit this module");
    return;
}
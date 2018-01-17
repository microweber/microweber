<?php


$settings = get_option('settings', $params['id']);

$defaults = array(
    'name' => 'Name',
    'role' => 'Role',
    'bio' => 'Bio',
    'file' => ''
);
$is_empty = false;
$data = json_decode($settings, true);

if (!$data) {
    $is_empty = true;
    print lnotif("Click on settings to edit this module");
  //  $data = array($defaults);
  //  return;
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

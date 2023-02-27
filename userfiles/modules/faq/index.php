<?php


//$settings = get_module_option('settings', 'faq');
 $settings = get_module_option('settings', $params['id']);


$defaults = array(
    'question' => '',
    'answer' => ''
);

$data = json_decode($settings, true);


if (!$data or count($data) == 0) {
    print lnotif("Click on settings to edit this module");
    return;
}


$module_template = get_module_option('data-template', $params['id']);


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

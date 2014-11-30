<?php

if (!isset($params['parent-module-id'])) {
    return;

}

$module_template = get_option('data-template', $params['parent-module-id']);

if ($module_template == false) {
    $module_template = 'default';
}
if ($module_template != false) {
    $template_file = module_templates($params['parent-module'], $module_template, true);
} else {
    $template_file = module_templates($params['parent-module'], 'default', true);
}


if (isset($template_file) and $template_file != false and is_file($template_file)) {
    include($template_file);
}
 
 
 

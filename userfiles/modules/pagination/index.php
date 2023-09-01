<?php
if (!isset($params['paging_param']) || !isset($params['pages_count'])) {
    return;
}

$pages_count = $params['pages_count'];
$paging_param = $params['paging_param'];

$pagination_links = paging("num={$pages_count}&paging_param={$paging_param}&return_as_array=1&show_first_last=1&limit=5");

$module_template = get_option('template', $params['id']);
if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}

if (!$module_template) {
    $module_template = get_template_framework();
}

$template_file = module_templates($config['module'], 'default');
if ($module_template != false) {
    $template_file_module = module_templates($config['module'], $module_template);
    if ($template_file_module) {
        $template_file = $template_file_module;
    }
}

// $settings = get_option('settings', $params['id']);

if (is_file($template_file)) {
    include($template_file);
}

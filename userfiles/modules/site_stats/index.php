<?php


$views = 0;
$content_id = 0;

if (isset($params['content-id'])) {
    $content_id = intval($params['content-id']);
    $views = stats_get_views_count_for_content($content_id);
}


$module_template = get_option('template', $params['id']);
if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}

if ($module_template != false) {
    $template_file = module_templates($config['module'], $module_template);
} else {
    $template_file = module_templates($config['module'], 'default');
}
if (isset($template_file) and is_file($template_file) != false) {
    include($template_file);
}


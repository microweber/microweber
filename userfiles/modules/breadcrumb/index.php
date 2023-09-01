<?php
$breacrumb_params = array();

if (isset($params['current-page-as-root'])) {
    $breacrumb_params['current-page-as-root'] = $params['current-page-as-root'];
}

$selected_start_depth = get_option('data-start-from', $params['id']);
if ($selected_start_depth) {
    $breacrumb_params['start_from'] = $selected_start_depth;
}

$data = breadcrumb($breacrumb_params);

$homepage = array(
    'url'=>site_url(),
    'title'=> _e('Home',true)

);

$homepage_get = app()->content_manager->homepage();
if($homepage_get){
    $homepage = array(
        'url'=>content_link($homepage_get['id']),
        'title'=>$homepage_get['title']
    );

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
if (is_file($template_file)) {
    include($template_file);
}

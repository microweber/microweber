<?php
$tablehtml = get_option('table_html', $params['id']);
$json = '';
if(!empty($tablehtml)){
	$tablehtml = preg_replace("/\r|\n/", "", $tablehtml);
	$tablehtml = html_entity_decode($tablehtml);
	// delete settings option key as html now saved as html in table_html key (instead of json format) to simplify and load faster
	mw()->option_manager->delete('settings', $params['id']);
} else {
	// Depricated
	$settings = get_option('settings', $params['id']);
	$json = ($settings? $settings : '');
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

if(is_admin() && empty($json) && empty($tablehtml)) print notif("Click here to edit table");
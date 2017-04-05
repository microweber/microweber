<?php




$tr = get_option('tr', $params['id']);
$th = get_option('th', $params['id']);



$th = json_decode($th, true);
$tr = json_decode($tr, true);

if (count($th) == 0) {
   			print lnotif(_e("Click on settings to edit this module", true));
			return;
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

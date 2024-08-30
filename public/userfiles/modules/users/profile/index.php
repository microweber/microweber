<?php
$user = get_user();

$countries = mw()->forms_manager->countries_list();



$module_template = get_option('template',$params['id']);
if($module_template == false and isset($params['template'])){
    $module_template =$params['template'];
}

if($module_template != false){
    $template_file = module_templates( $config['module'], $module_template);
}
else {
    $template_file = module_templates( $config['module'], 'default');
}

if(isset($template_file) and is_file($template_file) != false){
    include($template_file);
} else {
    $template_file = module_templates( $config['module'], 'default');
    include($template_file);
}


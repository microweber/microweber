<?php


use MicroweberPackages\Option\Models\ModuleOption;

$settings = get_module_option('settings', $params['id']);

$defaults = array(
    'name' => 'Name',
    'role' => 'Role',
    'bio' => 'Bio',
    'website' => '',
    'file' => ''
);
$is_empty = false;
$data = json_decode($settings, true);

if(!$data){
$data = array();
}

if (count($data) == 0) {
    $is_empty = true;
    print lnotif("Click on settings to edit this module");
  //  $data = array($defaults);
  //  return;
}

if(!empty($data)){
    //fill keys
    foreach ($data as $key => $value) {
        foreach ($defaults as $key2 => $value2) {
            if (!isset($data[$key][$key2])) {
                $data[$key][$key2] = $value2;
            }
        }
    }
}


$module_template = get_module_option('template', $params['id']);



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

<?php   $module_template = get_option('data-template', $params['id']);
if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}
if ($module_template != false) {
    $template_file = module_templates($config['module'], $module_template);
} else {
    $template_file = module_templates($config['module'], 'default');
}


$settings = get_option('settings', $params['id']);

if($settings == false){
    if(isset($params['settings'])){
        $settings = $params['settings'];
        $json = json_decode($settings, true);

    }
    else{

      $json = array(
        'title' => '',
        'id' => 'tab-'.uniqid(),
        'icon' => ''
      );
    }
}
else{
    $json = json_decode($settings, true);
}





if (is_file($template_file)) {
   include($template_file);
}

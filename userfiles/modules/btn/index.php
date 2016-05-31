<?php

    $style =  get_option('button_style', $params['id']);
    $size =  get_option('button_size', $params['id']);
    $action =  get_option('button_action', $params['id']);
    $action_content =  get_option('popupcontent', $params['id']);
    $url =  get_option('url', $params['id']);
    $blank =   get_option('url_blank', $params['id']);
    $text =  get_option('text', $params['id']);

     if ($text == ''){ $text = 'Button';}
     if ($style == ''){ $style = 'btn-default';}

     if($size == false and isset($params['button_size'])){
        $size = $params['button_size'];

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


if (isset($template_file) and is_file($template_file) != false) {
    include($template_file);
} else {
    $template_file = module_templates($config['module'], 'default');
    include($template_file);
}

 

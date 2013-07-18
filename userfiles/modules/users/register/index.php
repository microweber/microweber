<?php 




$template = get_option('data-template', $params['id']);

if (($template == false or ($template == '')) and isset($params['template'])) {

 $template =  $params['template'];

}



$template_file = false;
if ($template != false and strtolower($template) != 'none') {
//
    $template_file = module_templates($params['type'], $template);

//
} else {
  $template_file = module_templates($params['type'], 'default');
}

 

 if(is_file($template_file)){
 include($template_file);
 }

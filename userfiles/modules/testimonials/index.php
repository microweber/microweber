<?php



$limit = get_option('limit', 'fourtestimonials');
$template = get_option('data-template', $params['id']);

if ($limit == false or $limit == '') {
    $limit = 250;
}

$interval = get_option('interval', 'fourtestimonials');


if ($interval == false or $interval == '') {
    $interval = 5;
}

if ($interval < 0.2) {
    $interval = 0.2;
}


$data = get_testimonials("no_limit=true"); 

$openquote = get_option('openquote', 'fourtestimonials');
$closequote = get_option('closequote', 'fourtestimonials');

$template_file = false;
if ($template != false and strtolower($template) != 'none') {
    $template_file = module_templates($config['module'], $template);
}
if ($template_file == false) {
    $template_file = module_templates($config['module'], 'default');
}
if($template_file != false and is_file($template_file)){
    include($template_file);
}
?>
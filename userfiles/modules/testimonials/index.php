<?php



$limit = get_option('limit', 'fourtestimonials');
$template = get_option('data-template', $params['id']);
if($template == false and isset($params['template'])){
$template	= $params['template'];
}

$show_testimonials_per_project = get_option('show_testimonials_per_project',$params['id']);


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
if(isset($params['limit'])){
	$limit = intval($params['limit']);
}
$testimonials_limit = get_option('testimonials_limit',$params['id']);
if($show_testimonials_per_project){
	
	$get = array();
	if(intval($testimonials_limit) > 0){
		$get['limit'] = $testimonials_limit;
	} else {
		$get['no_limit'] = true;
	}
	
	$get['project_name'] = $show_testimonials_per_project;
	$data = get_testimonials($get);

} else {
	$get = array();
	if(intval($testimonials_limit) > 0){
		$get['limit'] = $testimonials_limit;
	} else {
		$get['no_limit'] = true;
	}
	$data = get_testimonials($get); 

}







if(empty($data)){
    return print lnotif(_e("Click here to edit Testimonials", true));
}

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
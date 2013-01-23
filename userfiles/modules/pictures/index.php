<?
 
if(!isset($params['to_table_id'])){
	$params['to_table_id'] = $params['id']; 
}

 if(isset($params['to_table_id']) == true): ?>
<? $data = get_pictures($content_id = $params['to_table_id'], $for = 'post'); 
 


$module_template = get_option('data-template',$params['id']);
if($module_template == false and isset($params['template'])){
	$module_template =$params['template'];
} 





if($module_template != false){
		$template_file = module_templates( $config['module'], $module_template);

} else {
		$template_file = module_templates( $config['module'], 'default');

}

//d($module_template );
if(isset($template_file) and is_file($template_file) != false){
 	include($template_file);
} else {
	
	print 'No default template for module '.$config['module'].' is found';
} ?><? endif; ?>
  
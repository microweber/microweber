<?

 
if(!isset($params['rel_id'])){
	$params['rel_id'] = $params['id']; 
}

if(isset($params['for'])){
	 $for = 'content';
} else {
 $for = 'modules';	
}


if(get_option('data-use-from-post', $params['id']) =='y'){
	 if(POST_ID != false){
	$params['content-id'] = POST_ID;
	 } else {
		 	$params['content-id'] = PAGE_ID;

	 }
}

if(isset($params['content-id'])){
	$params['rel_id'] = $params['content-id']; 
	 $for = 'content';
}

 
 if(isset($params['rel_id']) == true): ?>
<? $data = get_pictures('rel_id='.$params['rel_id'].'&for='.$for); 
 
 if(empty( $data)){
	 $data = array(); 
	 $data[0]['id'] = 0;
	 $data[0]['filename'] =  $config['url_to_module'].'no_image.png';
	  
 }
 
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
	?>

<div class="mw-notification mw-warning">
  <div> <span class="ico ioptions"></span> <span><? print 'No default template for module '.$config['module'].' is found'; ?></span> </div>
</div>
<?
	
} ?>
<? endif; ?>

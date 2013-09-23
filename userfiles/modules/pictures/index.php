<?php

  $no_img = false;
  
  
if(isset($params['rel']) and trim(strtolower(($params['rel']))) == 'post' and defined('POST_ID')){
	$params['rel_id'] = POST_ID; 
	$params['for'] = 'content';
}

if(isset($params['rel']) and trim(strtolower(($params['rel']))) == 'page' and defined('PAGE_ID')){
	$params['rel_id'] = PAGE_ID; 
	$params['for'] = 'content';
}

if(isset($params['rel']) and trim(strtolower(($params['rel']))) == 'content' and defined('CONTENT_ID')){
	$params['rel_id'] = CONTENT_ID; 
	$params['for'] = 'content';
}





if(isset($params['for'])){
	 $for = $params['for'];
} else {
 $for = 'modules';	
}


/*if($for != 'content' and !isset($params['rel_id'])){
	$params['rel_id'] = $params['id']; 
} else if($for == 'content' and !isset($params['rel_id'])){
	$params['rel_id'] = CONTENT_ID; 
	$params['for'] = 'content';
}
*/


 


if(get_option('data-use-from-post', $params['id']) =='y'){
	 if(POST_ID != false){
	$params['content-id'] = POST_ID;
	 } else {
	 $params['content-id'] = PAGE_ID;

	 }
} elseif(!isset($params['for']) and get_option('data-use-from-post', $params['id']) ==''){
	  $for = 'modules';
	   $params['rel_id'] = $params['id']; 
} else {
	
	 if(!isset($params['for'])){
	 $for = 'modules';
	  $params['rel_id'] = $params['id']; 
	 } else {
		 $for = $params['for'];
	 }

}
 
if(isset($params['content-id'])){
	$params['rel_id'] = intval($params['content-id']); 
	 $for = 'content';
}
if($params['rel_id'] == false){
$params['rel_id'] = 0;	
}
$for_id = $params['rel_id'];
    
 if(isset($params['rel_id']) == true): ?>
<?php $data = get_pictures('rel_id='.$params['rel_id'].'&for='.$for);
  
 if(!is_array( $data)){
	/* $data = array(); 
	 $data[0]['id'] = 0;
	 $data[0]['filename'] =  $config['url_to_module'].'no_image.png';*/
         $no_img = true;
 }
 
$module_template = get_option('data-template',$params['id']);
if($module_template == false and isset($params['template'])){
	$module_template =$params['template'];
} 



?>
<?php if(defined('IN_EDIT')): ?>
	<a href="javascript:;" onclick="mw.drag.module_view('quick_add');">+Add picture</a>
<?php  endif; ?>


<?php

if($module_template != false){
    $template_file = module_templates( $config['module'], $module_template);
} else {
    $template_file = module_templates( $config['module'], 'default');
}
 
if(isset($no_img) and ($no_img) != false){
     print lnotif( "<div class='pictures-module-default-view mw-open-module-settings thumbnail'><img src='" .$config['url_to_module'] . "pictures.png' /></div>"); 

}
else if($no_img  != true and !empty($data) and isset($template_file) and is_file($template_file) != false){
 	include($template_file);
} else  {
	?>
	 
<?php  print lnotif("No template found. Please choose template."); ?>

<?php
	
} ?>
<?php endif; ?>
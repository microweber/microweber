<?php


 

$for = $config['module'];
$for_module_id = $params['id'];

 $use_from_post = get_option('data-use-from-post', $params['id']) =='y';
 	  $use_from_post_forced = false;
 
 if(isset($params['rel'])){
	 if(((trim($params['rel']) == 'post') or trim($params['rel']) == 'post') or trim($params['rel']) == 'post'){
	  $use_from_post = true;
	  $use_from_post_forced = 1;
		unset($params['rel']);
	 }
 }
 
if( $use_from_post){
 

 
 if(POST_ID != false){
	
	$params['content-id'] = POST_ID;
	 } else {
	$params['content-id'] = PAGE_ID;

	 }	 
}

 
if(isset($params['content-id'])){
	$for_module_id = $for_id = $params['content-id'];
	 $for = 'content';
} else {
	$for_module_id = $for_id = $params['id']; 
	 $for = 'modules';
}
  ?>

<microweber module="pictures/admin_backend" for="<?php print $for ?>" for-id="<?php print $for_id ?>" >

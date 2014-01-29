<?php
only_admin_access();


if(url_param('add_template')){
	$install = url_param('add_template');
	
	$update_api = new \Microweber\Update();
 	$result = $update_api -> install_template($install);
	 print_r($result);
 
} else {  
	

	  	$update_api = new \Microweber\Update();
		$result = $update_api -> browse();
}
		// print_r($result);
	 
	 
 
 ?>
 browsen ew stuff
<?php if(isset($result['templates']) and is_array($result['templates']) == true): ?>
<?php foreach($result['templates'] as $k=>$item): ?>
<?php if(isset($item['name']) and isset($item['dir_name'])): ?>
<?php print $item['name']; ?>
<a href="<?php print admin_url() ?>view:updates__browse/add_template:<?php print ($item['dir_name']) ?>" class="mw-ui-btn">
    <?php _e("Install"); ?>
    </a>
<?php endif; ?>
<hr>
<?php endforeach; ?>
<?php endif; ?>

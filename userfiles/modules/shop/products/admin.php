<div class="<?php print $config['module_class']; ?>">
<?php
	
	$params['is_shop'] = 'y';
		$dir_name = normalize_path(MODULES_DIR);
$posts_mod =  $dir_name.'posts'.DS.'admin_live_edit.php';;
include($posts_mod);
   ?>   
</div>
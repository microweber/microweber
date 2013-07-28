<div class="<?php print $config['module_class']; ?>">
  <?php
	
	$params['is_shop'] = 'y';
	$params['subtype'] = 'product';
		$dir_name = normalize_path(MW_MODULES_DIR);
$posts_mod =  $dir_name.'posts'.DS.'admin_live_edit.php';;
include($posts_mod);
   ?>
</div>

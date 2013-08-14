<div class="<?php print $config['module_class']; ?>">
<?php
$params['subtype'] ='product';
$params['is_shop'] = 'y';
		$dir_name = normalize_path(MW_MODULES_DIR);
$posts_mod =  $dir_name.'posts'.DS.'index.php';;
include($posts_mod);
   ?>   
</div>
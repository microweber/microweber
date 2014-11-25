<div class="<?php print $config['module_class']; ?>">
<?php


 
$add_cart_text = get_option('data-add-to-cart-text', $params['id']);
if( $add_cart_text == false){  
$add_cart_text =  'Add to cart'; 
}

      

$params['subtype'] ='product';
$params['is_shop'] = 'y';
		$dir_name = normalize_path(MW_MODULES_DIR);
$posts_mod =  $dir_name.'posts'.DS.'index.php';;
include($posts_mod);
   ?>   
</div>
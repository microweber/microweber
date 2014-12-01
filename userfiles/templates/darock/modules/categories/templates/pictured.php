<?php

/*

type: layout

name: pictured

description: Categories Navigation with pictuires

*/

?>
<?php
    $params['ul_class'] = 'nav nav-list';
	$params['ul_class_deep'] = 'nav nav-list';





    if(isset($params['content_id'])){
	 
	 $cat_params = array();
	 
	  
	   if(isset($params['parent'])){
		     $cat_params['parent'] = $params['parent'];
	   } else {
		 $cat_params['rel'] = 'content';
	  $cat_params['rel_id'] = $params['content_id'];   
	   }
	 
	  
      $categories = get_categories($cat_params);
	  
 
	  
	  
    }  else {
       $categories = get_categories('rel=content');
    }
	
	 


?>
 
<?php if($categories): ?>
<div class="categories-grid">
  <?php foreach($categories as $category): ?>
  <a href="<?php print category_link($category['id']); ?>" class="category-grid-item">
    <span class="category-grid-item-content">
      <?php $cat_image = get_picture($category['id'],'categories');  ?>
      <span class="category-image" style="background-image: url('<?php if($cat_image){ print $cat_image; }  ?>');"></span> <strong><?php print $category['title']; ?></strong>
    </span>
  </a>
  <?php endforeach; ?>
</div>
<?php endif;?>

<?php

/*

type: layout

name: shop2 layout

description: shop2 layout









*/



?>
 
<?php include TEMPLATE_DIR."header.php" ?>
    <div class="wrap">
      <div id="main_content">
        <div id="user_sidebar">
          <h3 class="user_sidebar_title nomargin"><a href="<? print page_link($page['id']); ?>"><? print ucwords($page['content_title']); ?></a></h3>
          <? 
		  $cat_params = array(); 
		  $cat_params['parent'] = intval($page['content_subtype_value']); //begin from this parent category
		  $cat_params['get_only_ids'] = false; //if true will return only the category ids
		  $categories = get_categories($cat_params); 
		  ?>
          <ul class="user_side_nav user_side_nav_nobg">
            <? foreach($categories as $category): ?>
            <li><a href="<? print get_category_url($category['id']); ?>"  class="<? is_active_category($category['id'],' active') ?>"  ><? print $category['taxonomy_value'] ?></a> </li>
            <? endforeach; ?>
          </ul>
           
           
           <block id="shop_sidebar">
           
            <block id="shop_sidebar2">
           
        </div>
        <!-- /#user_sidebar -->
        <div id="main_side">
        
          <block id="shop_main_content">
 
         <editable page="<? print $page['id'] ?>"  field="content_body">default text to edit </editable>
         
         
         <editable page="<? print $page['id'] ?>"  field="custom_field_test1">test1</editable>
         
         <? if($post): ?>
         <?php include TEMPLATE_DIR."post.php" ?>         
         <? else: ?>
         <?php include TEMPLATE_DIR."posts.php" ?>     
         <? endif; ?>
        
         <block id="shop_main_content_below">
        
         
        </div>
      </div>
    </div>
    <?php include  TEMPLATE_DIR."footer.php" ?>

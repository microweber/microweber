<?php
only_admin_access();
$is_shop = false;

if(isset($params['is_shop']) and $params['is_shop'] == 'y'){
	$is_shop = 1;
}

		$dir_name = normalize_path(MODULES_DIR);
$posts_mod =  $dir_name.'posts'.DS.'admin_live_edit_tab1.php';;
 ?>
 <script type="text/javascript">
 
 mw.add_new_content_live_edit = function($cont_type){
	   $('#mw_posts_add_live_edit').removeAttr('data-content-id');
	 	 $('#mw_posts_add_live_edit').attr('from_live_edit',1);
		 if($cont_type == undefined){
			 $('#mw_posts_add_live_edit').removeAttr('content_type');

		 } else {
			 if($cont_type == 'page'){
				 $('#mw_posts_add_live_edit').removeAttr('subtype');
			 } else {
				 $('#mw_posts_add_live_edit').attr('subtype','post');
 
			 }
			 
			 
			 
			 $('#mw_posts_add_live_edit').attr('content_type',$cont_type);
 
		 }

      mw.load_module('content/edit_page', '#mw_posts_add_live_edit')
 } 
 
 
 </script>
<div class="mw_simple_tabs mw_tabs_layout_simple">
<!--    <a href="<?php print admin_url('view:').$params['module']  ?>" class="mw-ui-btn right relative" style="z-index: 2;margin:13px 13px 0 0;" target="_blank">Add post</a>
-->

<?php 
$set_content_type_mod = 'page';
if(isset($params['global']) and $params['global'] != false){
	$set_content_type_mod_1 =  get_option('data-content-type', $params['id']); 
	if($set_content_type_mod_1 != false and $set_content_type_mod_1 != ''){
	$set_content_type_mod = $set_content_type_mod_1;	
	}
}

?>

 

<?php   if(isset($params['global'])){ ?>
<a href="javascript:;"
    class="mw-ui-btn mw-ui-btn-green"
    onclick="mw.simpletab.set(mwd.getElementById('add_new_post'));mw.add_new_content_live_edit('<?php print addslashes($set_content_type_mod); ?>');"
    style="position: absolute;top: 12px;right: 12px;z-index: 2;"><span class="ico iplus"></span><span class="ico i<?php print trim($set_content_type_mod); ?>"></span>Add new <?php print ucwords($set_content_type_mod); ?></a>

<?php } else if($is_shop){  ?>
 
<a href="javascript:;"
    class="mw-ui-btn mw-ui-btn-green"
    onclick="mw.simpletab.set(mwd.getElementById('add_new_post'));mw.add_new_content_live_edit('product');"
    style="position: absolute;top: 12px;right: 12px;z-index: 2;"><span class="ico iplus"></span><span class="ico iproduct"></span>New Product</a>

<?php } else{ ?>

<a href="javascript:;"
    class="mw-ui-btn mw-ui-btn-green"
    onclick="mw.simpletab.set(mwd.getElementById('add_new_post'));mw.add_new_content_live_edit('post');"
    style="position: absolute;top: 12px;right: 12px;z-index: 2;"><span class="ico iplus"></span><span class="ico ipost"></span>New Post</a>

<?php } ?>

  <ul class="mw_simple_tabs_nav">
    <li><a href="javascript:;" class="actSive">
      <?php if($is_shop): ?>
      Products
      <?php else:  ?>
      Posts
      <?php endif;  ?>
      list</a></li>
    <li><a href="javascript:;">Skin/Template</a></li>
    <li id="add_new_post" style="display: none;"><a href="javascript:;"></a></li>


  </ul>


  <div class="tab">
    <?php include_once($posts_mod); ?>
  </div>
  <div class="tab">
  <?php if(isset($params['global'])) : ?>
 
    <module type="admin/modules/templates" id="posts_list_templ" for-module="posts" />
     <?php else:  ?>
     <module type="admin/modules/templates" id="posts_list_templ"  />
      <?php endif;  ?>
  </div>
   <div class="tab">
   <?php 
   
   if(isset($params['is_shop']) and $params['is_shop'] == 'y'){
	    $add_post_q = 'subtype="product" is_shop=y ';
   } else {
	    $add_post_q = 'subtype=post ';
   }
  
   if(isset($params['page-id'])){
	    $add_post_q  .=' data-parent-page-id='.$params['page-id'];
   }
   
  $posts_parent_page =  get_option('data-page-id', $params['id']); 
   $posts_parent_category =  get_option('data-category-id', $params['id']);

  if($posts_parent_page != false and intval($posts_parent_page) > 0){
	  $add_post_q  .=' data-parent-page-id='.intval($posts_parent_page);
  } else  if(isset($params['page-id'])){
	    $add_post_q  .=' data-parent-page-id='.$params['page-id'];
   } 
   
   
   
  
  if($posts_parent_page != false and $posts_parent_category != false and intval($posts_parent_category) > 0){
	  
	  $str0 = 'table=categories&limit=1000&data_type=category&what=categories&' . 'parent_id=[int]0&rel_id=' . $posts_parent_page;
	  $page_categories = get($str0);
					$sub_cats = array();
					$page_categories = get($str0);
					// d($page_categories);
						if(isarr($page_categories)){
						foreach ($page_categories as $item_cat){
							//d($item_cat);
						$sub_cats[] = $item_cat['id'];
						$more =    get_category_children($item_cat['id']);
						if($more != false and isarr($more)){
							foreach ($more as $item_more_subcat){
								$sub_cats[] = $item_more_subcat;
							}
						}
					 
						}
					}
				 
				if(isarr($sub_cats) and in_array($posts_parent_category,$sub_cats)){
						
	  	    $add_post_q  .=' selected-category-id='.intval($posts_parent_category);
				}
	  
  }
  
   
  
   ?>  
    <div  <?php print $add_post_q ?> id="mw_posts_add_live_edit"></div>
  </div>
  <div class="mw_clear"></div>
  <div class="vSpace"></div>
</div>

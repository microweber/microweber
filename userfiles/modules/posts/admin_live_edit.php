<style>
.mw_posts_edit_live_edit form.mw_admin_edit_content_form {
	width: 650px;
	padding: 0px;
}

.mw_posts_edit_live_edit .mw-title-field {
	width: 320px;
}
.mw_posts_edit_live_edit iframe.mw-editor-iframe-loaded {
	width: 390px;
}
.mw_posts_edit_live_edit #mw-scaleeditor, #mw_posts_add_live_edit .go-live {
	display: none;
}

.mw_posts_edit_live_edit #edit_post_select_category .input {
	width: 110px;
}
.mw_posts_edit_live_edit .mw-ui-category-selector {
	width: 380px;
}
.mw_posts_edit_live_edit .pages_tree_link_text {
	max-width: 100%;
}
#mw_posts_add_live_edit form.mw_admin_edit_content_form {
	width: 650px;
	padding: 0;
}
#mw_posts_add_live_edit .mw-title-field {
	width: 320px;
}
#mw_posts_add_live_edit iframe.mw-editor-iframe-loaded {
	width: 390px;
}
#mw_posts_add_live_edit #mw-scaleeditor, #mw_posts_add_live_edit .go-live {
	display: none;
}


#mw_posts_add_live_edit #edit_post_select_category .input {
	width: 110px;
}
#mw_posts_add_live_edit .mw-ui-category-selector {
	width: 380px;
}
#mw_posts_add_live_edit .pages_tree_link_text {
	max-width: 100%;
}
</style>
<?php
only_admin_access();
$is_shop = false;

if(isset($params['is_shop']) and $params['is_shop'] == 'y'){
	$is_shop = 1;
}

		$dir_name = normalize_path(MW_MODULES_DIR);
$posts_mod =  $dir_name.'posts'.DS.'admin_live_edit_tab1.php';;
 ?>
<?php 
$set_content_type_mod = 'page';
if(isset($params['global']) and $params['global'] != false){
	$set_content_type_mod_1 =  get_option('data-content-type', $params['id']); 
	if($set_content_type_mod_1 != false and $set_content_type_mod_1 != ''){
	$set_content_type_mod = $set_content_type_mod_1;	
	}
}

?>
<script type="text/javascript">

 mw.add_new_content_live_edit = function($cont_type){
	 	   mw.simpletab.set(mwd.getElementById('add_new_post'));

	   $('#mw_posts_create_live_edit').removeAttr('data-content-id');
	 	 $('#mw_posts_create_live_edit').attr('from_live_edit',1);
		 if($cont_type == undefined){
			 $('#mw_posts_create_live_edit').removeAttr('content_type');

		 } else {
			 if($cont_type == 'page'){
				 $('#mw_posts_create_live_edit').removeAttr('subtype');
			 } else {
				 $('#mw_posts_create_live_edit').attr('subtype',$cont_type);
 
			 }
			 $('#mw_posts_create_live_edit').attr('content_type',$cont_type);
		 }
		 
	     $('#mw_posts_edit_live_edit').attr('data-content-id', 0); 

		 $('#mw_posts_create_live_edit').attr('quick_edit',1);
		 	 	 $('#mw_posts_create_live_edit').removeAttr('live_edit');

      mw.load_module('content/edit_page', '#mw_posts_create_live_edit', function(){
        parent.mw.tools.modal.resize("#"+thismodal.main[0].id, 710, mw.$('#settings-container').height()+25, false);
      })
 	}
  mw.manage_live_edit_content = function($id){
	  mw.simpletab.set(mwd.getElementById('manage_posts'));
	   if($id != undefined){
			 $('#mw_posts_manage_live_edit').attr('module-id',$id);
       }
	   $('#mw_posts_manage_live_edit').removeAttr('just-saved');
	   
	   
	   
	   mw.load_module('content/manage_live_edit', '#mw_posts_manage_live_edit', function(){
             parent.mw.tools.modal.resize("#"+thismodal.main[0].id, 710, mw.$('#settings-container').height()+25, false);
	   })
  }
  mw.edit_content_live_edit = function($cont_id){
	   mw.simpletab.set(mwd.getElementById('edit_posts'));
	     $('#mw_posts_edit_live_edit').attr('data-content-id', $cont_id);
	 	 $('#mw_posts_edit_live_edit').removeAttr('live_edit');
		 		 $('#mw_posts_edit_live_edit').attr('quick_edit',1);

	     mw.load_module('content/edit_page', '#mw_posts_edit_live_edit', function(){
            parent.mw.tools.modal.resize("#"+thismodal.main[0].id, 710, mw.$('#settings-container').height()+25, false);
	     });
  }
  
   mw.delete_content_live_edit = function(a, callback){
	   
	   
	 mw.tools.confirm("<?php _e("Do you want to delete this post"); ?>?", function(){
		  var arr = $.isArray(a) ? a : [a];
		var obj = {ids:arr}
		$.post(mw.settings.site_url + "api/content/delete", obj, function(data){
		  typeof callback === 'function' ? callback.call(data) : '';
		  $('.manage-post-item-'+a).fadeOut();
		   mw.notification.warning("<?php _e('Content was sent to Trash'); ?>.");
		  mw.reload_module_parent('posts')
		  mw.reload_module_parent('shop/products')
		  mw.reload_module_parent('content')
		  
		  
		  
		});
       
   });
   
   
   
   
    
  }
  
  
  
  </script>

<div class="mw_simple_tabs mw_tabs_layout_simple"> 
  <?php   if(isset($params['global'])){ ?>
  <a href="javascript:;"
    class="mw-ui-btn mw-ui-btn-green"
    onclick="mw.simpletab.set(mwd.getElementById('add_new_post'));mw.add_new_content_live_edit('<?php print addslashes($set_content_type_mod); ?>');"
    style="position: absolute;top: 12px;right: 12px;z-index: 2;"><span class="ico iplus"></span><span class="ico i<?php print trim($set_content_type_mod); ?>"></span><?php _e("Add new"); ?> <?php print ucwords($set_content_type_mod); ?></a>
  <?php } else if($is_shop){  ?>
  <a href="javascript:;"
    class="mw-ui-btn mw-ui-btn-green"
    onclick="mw.simpletab.set(mwd.getElementById('add_new_post'));mw.add_new_content_live_edit('product');"
    style="position: absolute;top: 12px;right: 12px;z-index: 2;"><span class="ico iplus"></span><span class="ico iproduct"></span><?php _e("New Product"); ?></a>
  <?php } else{ ?>
  <a href="javascript:;"
    class="mw-ui-btn mw-ui-btn-green"
    onclick="mw.simpletab.set(mwd.getElementById('add_new_post'));mw.add_new_content_live_edit('post');"
    style="position: absolute;top: 12px;right: 12px;z-index: 2;"><span class="ico iplus"></span><span class="ico ipost"></span><?php _e("New Post"); ?></a>
  <?php } ?>
 <ul class="mw_simple_tabs_nav">
     <li id="manage_posts"  ><a href="javascript:;"  onclick="javascript:mw.manage_live_edit_content('<?php print $params['id'] ?>');"><?php _e("Manage"); ?></a></li>

    <li><a href="javascript:;" class="actSive"> 
      <!-- <?php if($is_shop): ?>
      Products
      <?php else:  ?>
      Posts
      <?php endif;  ?>--> 
      
      <?php _e("Settings"); ?></a></li>
    <li><a href="javascript:;"><?php _e("Skin/Template"); ?></a></li>
 
    <li id="add_new_post" style="display: none;"><a href="javascript:;"></a></li>
    <li id="edit_posts" style="display: none;"><a href="javascript:;"></a></li>
  </ul>
  <div class="tab">
    <?php
   
   if(isset($params['is_shop']) and $params['is_shop'] == 'y'){
	    $add_post_q = 'subtype="product" is_shop=y ';
   } else {
	    $add_post_q = 'subtype=post ';
   }
   if(isset($params['id'])){
	   $add_post_q  .=' module-id="'. $params['id'].'";';
	  
   }
   
   
  
   if(isset($params['page-id'])){
	    $add_post_q  .=' data-parent-page-id='.$params['page-id'];
   }
    if(isset($params['related'])){
	    $add_post_q  .=' related='.$params['related'];
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
						if(is_array($page_categories)){
						foreach ($page_categories as $item_cat){
							//d($item_cat);
						$sub_cats[] = $item_cat['id'];
						$more =    get_category_children($item_cat['id']);
						if($more != false and is_array($more)){
							foreach ($more as $item_more_subcat){
								$sub_cats[] = $item_more_subcat;
							}
						}
					 
						}
					}
				 
				if(is_array($sub_cats) and in_array($posts_parent_category,$sub_cats)){
						
	  	    $add_post_q  .=' selected-category-id='.intval($posts_parent_category);
				}
	  
  }
  
   
  
   ?>  
    <module type="content/manage_live_edit"  <?php print $add_post_q ?> id="mw_posts_manage_live_edit" />
  </div>
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
	    $add_post_q = '  ';
   }
  
   ?>
    <div <?php print $add_post_q ?> id="mw_posts_create_live_edit"></div>
  </div>
  <div class="tab">
    <div  id="mw_posts_edit_live_edit" class="mw_posts_edit_live_edit"></div>
  </div>
  <div class="mw_clear"></div>
  <div class="vSpace"></div>
</div>

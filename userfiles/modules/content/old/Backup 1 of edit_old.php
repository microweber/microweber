<?php
 
  only_admin_access();

  $rand = uniqid();
  $data = false;
  $just_saved = false;
  $is_new_content = false;
  $is_current = false;
  $is_live_edit = false;
  if(!isset($is_quick)){
      $is_quick=false;
  }
 
  if(isset($params['live_edit'])){
    $is_live_edit = $params['live_edit'];
  } elseif(isset($params['from_live_edit'])){
   $is_live_edit = $params['from_live_edit'];
  }

  if(isset($params['quick_edit'])){
    $is_quick = $params['quick_edit'];
  }
  if($is_live_edit == true){
	   $is_quick = false;
  }
  
  if(isset($params['just-saved'])){
    $just_saved = $params['just-saved'];
  }
  if(isset($params['is-current'])){
    $is_current = $params['is-current'];
  }
  if(isset($params['page-id'])){
    $data = get_content_by_id(intval($params["page-id"]));
  }
  if(isset($params['content-id'])){
    $data = get_content_by_id(intval($params["content-id"]));
  }
  $recommended_parent = false;
  if(isset($params['recommended_parent']) and $params['recommended_parent'] != false){
    $recommended_parent = $params['recommended_parent'];
  }
  $categories_active_ids =false;
  $title_placeholder = false;

  /* FILLING UP EMPTY CONTENT WITH DATA */
  if($data == false or empty($data )){
     $is_new_content = true;
     include('_empty_content_data.php');
  }
  
  if(isset($params['add-to-menu'])){
    $data['add_to_menu'] = (($params["add-to-menu"]));
  }
  
  

/* END OF FILLING UP EMPTY CONTENT  */
 

/* SETTING PARENT AND ACTIVE CATEGORY */
$forced_parent = false;
if(intval($data['id']) == 0 and intval($data['parent']) == 0 and isset($params['parent-category-id']) and $params['parent-category-id'] != 0 and !isset($params['parent-page-id'])){
      $cat_page = get_page_for_category($params['parent-category-id']);
	  if(is_array($cat_page) and isset($cat_page['id'])){
		$forced_parent = $params['parent-page-id'] = $cat_page['id'];
	  }
}

if(intval($data['id']) == 0 and intval($data['parent']) == 0 and isset($params['parent-page-id'])){
    $data['parent'] = $params['parent-page-id'];
    if(isset($params['subtype']) and $params['subtype'] == 'product'){
        $parent_content = get_content_by_id($params['parent-page-id']);
       // if(!isset($parent_content['is_shop']) or $parent_content['is_shop'] != 'y'){
			  
           // $data['parent'] = 0;
       // }
    }
    if(isset($params['parent-category-id']) and $params['parent-category-id'] != 0){
        $categories_active_ids =$params['parent-category-id'];
    }
}
else if(intval($data['id']) != 0){
    $categories  =get_categories_for_content($data['id']);
   if(is_array($categories)){
	 $c = array();
	 foreach($categories as $category){
		 $c[] = $category['id'];
	 }
 	 $categories_active_ids = implode(',',$c);
    }

}
/* END OF SETTING PARENT AND ACTIVE CATEGORY  */



/* SETTING PARENT AND CREATING DEFAULT BLOG OR SHOP IF THEY DONT EXIST */
if(intval($data['id']) == 0 and intval($data['parent']) == 0){
	$parent_content_params = array();
	$parent_content_params['subtype'] = 'dynamic';
	$parent_content_params['content_type'] = 'page';
	$parent_content_params['limit'] = 1;
	$parent_content_params['one'] = 1; 
	$parent_content_params['parent'] = 0;
	$parent_content_params['fields'] = 'id';
//	$parent_content_params['is_active'] = 'y';
	$parent_content_params['order_by'] = 'posted_on desc, updated_on desc';
	  
	if(isset($params['subtype']) and $params['subtype'] == 'post'){
		$parent_content_params['is_shop'] = 'n';
		$parent_content_params['is_home'] = 'n';
	    $parent_content = get_content($parent_content_params);
 
		 if(!isset($parent_content['id'])){
			 unset($parent_content_params['parent']);
			 $parent_content = get_content($parent_content_params);

		 }
		 if(isset($parent_content['id'])){
			 $data['parent'] = $parent_content['id'];
		 } else {
			  mw()->content_manager->create_default_content('blog');
			  $parent_content_params['no_cache'] = true;
			  $parent_content = get_content($parent_content_params);
			  
		 }
	} elseif(isset($params['subtype']) and $params['subtype'] == 'product'){
		$parent_content_params['is_shop'] = 'y';
	    $parent_content = get_content($parent_content_params);
		
		 if(isset($parent_content['id'])){
			 $data['parent'] = $parent_content['id'];
		 } else {
			  mw()->content_manager->create_default_content('shop');
			  $parent_content_params['no_cache'] = true;
			  $parent_content = get_content($parent_content_params);
		 }
	} 
	if(isset($parent_content) and isset($parent_content['id'])){
			 $data['parent'] = $parent_content['id'];
	 } 
	

} elseif($forced_parent == false and (intval($data['id']) == 0 and intval($data['parent']) != 0) and isset($data['subtype']) and $data['subtype'] == 'product'){
	 
	 //if we are adding product in a page that is not a shop
	 $parent_shop_check =  get_content_by_id($data['parent']);
	 if(!isset($parent_shop_check['is_shop']) or $parent_shop_check['is_shop'] != 'y'){
		 $parent_content_shop = get_content('order_by=updated_on desc&one=true&is_shop=y');
		  if(isset($parent_content_shop['id'])){
			 $data['parent'] = $parent_content_shop['id'];
		 }
	 }
	 
} elseif($forced_parent == false and (intval($data['id']) == 0 and intval($data['parent']) != 0) and isset($data['subtype']) and $data['subtype'] == 'post'){
	 
	 //if we are adding product in a page that is not a shop
	 $parent_shop_check =  get_content_by_id($data['parent']);
	
	 if(!isset($parent_shop_check['subtype']) or $parent_shop_check['subtype'] != 'dynamic'){
		 $parent_content_shop = get_content('order_by=updated_on desc&one=true&subtype=dynamic&is_shop=n');
		  if(isset($parent_content_shop['id'])){
			  $data['parent'] = $parent_content_shop['id'];
		 }
	 }
	 
}
 
/* END OF SETTING PARENT AND CREATING DEFAULT BLOG OR SHOP IF THEY DONT EXIST */

 $module_id = $params['id'];
 
?>
<?php if($just_saved!=false) : ?>
<?php endif; ?>
<?php $content_edit_modules = mw('ui')->admin_content_edit(); ?>
<?php $modules = array(); ?>
<?php if (!empty($content_edit_modules) and !empty($data)) {
    foreach ($content_edit_modules as $k1=>$content_edit_module) {
		foreach ($data as $k=>$v) {
			if(isset($content_edit_module[$k])){
				$v1 = $content_edit_module[$k];
				$v2 = $v;
				if(trim($v1) == trim($v2)){
				 $modules[] = $content_edit_module['module'];
				}
			}
			
		}
    }
	$modules = array_unique($modules);
}
  
?>
<?php if(!empty($modules)): ?>
<?php foreach($modules as $module) : ?>
<?php print load_module($module,$data); ?>
<?php endforeach; ?>
<?php else:  ?>
<?php include __DIR__ . DS . 'edit_default.php';  ?>
<?php endif; ?>
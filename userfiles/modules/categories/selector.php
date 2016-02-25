<script type="text/javascript">
<?php include_once( mw_includes_path() . 'api/treerenderer.php'); ?>
</script>
<?php if(isset($params['show_edit_categories_admin_link'])): ?>
<style>
.category-tree-icon-category-editable-hover-crtl {
	display: none;
}
</style>
<script>
 


$( document ).ready(function() {
      mw.$("#<?php print $params['id'] ?> .category_element label").hover(function () {
		  
				$('.category-tree-icon-category-editable-hover-crtl:first',this).show();		  
		  }, function () {
              $('.category-tree-icon-category-editable-hover-crtl:first',this).hide();		  

     });

})
 
</script>
<?php endif; ?>
<?php 


 

$field_name="categories";
$selected = 0;


if(isset($params['field-name'])){
$field_name = $params['field-name'];
}

if(isset($params['selected-id'])){
$selected = intval($params['selected-id']);
}

$rand=uniqid();

$orig_params = ($params);

 //$rand =  $params['id'];;
if (!isset($params['for'])) {

	$for = 'content';
} else {
	$for = $params['for'];
}


if (!isset($params['rel_type'])) {
	$for = 'content';

} else {
	$for = $params['rel_type'];
}

$is_shop = '';
$active_cats = array();

if(isset($params['data-subtype']) and $params['data-subtype'] == 'product'){
	$params['is_shop'] = 'y';
} else {
 	//$params['is_shop'] = 0;
}


if (isset($params['is_shop'])) {
	$is_shop = '&is_shop=' . $params['is_shop'];
}
if (!isset($params['rel_id']) and isset($params['rel-id'])) {
	$params['rel_id'] = $params['rel-id'];
}
if (!isset($params['rel_id'])) {
	$rel_id = '';
	if (!isset($params['rel_id'])) {

	}

	if (isset($params['parent-category-id'])) {
		$active_cats [] = $params['parent-category-id'];
	}

} else {
	$rel_id = '&rel_id=' . $params['rel_id'];
}

?>
<?php
$cats_str = array();
$cats_ids = array();
$cats__parents = array();
$is_ex1 = array();
$for = mw()->database_manager->assoc_table_name($for);



if (isset($params['is_shop']) and trim($params['is_shop']) =='y') {
  $is_ex = get_content('parent=0&content_type=page&is_shop=1&limit=1000');

} else {
	  $is_ex = get_content('content_type=page&is_shop=0&limit=1000');
}
 
if(is_array($is_ex)){
	foreach ($is_ex as $item) {
		$cats__parents[] = $item['id'];
	}
}
if(!isset($params['field-name']) or $params['field-name'] == false){
    $params['field-name'] = 'categories' ;
 }
 
?>
<?php


 if(!empty($cats__parents)): ?>
<?php

$active_cats1 = array();
foreach ($cats__parents as $item1) {
	$active_cats1[] = $item1;
}
$tree = array();
$tree['include_categories'] = 1;
$tree['parent'] = '0';



if (isset($orig_params['is_shop']) and trim($orig_params['is_shop']) == 'y') {

	$tree['is_shop'] = 1;
	$tree['parent'] = 'any';


}
 
if (isset($params['active_ids'])) {
	$tree['active_ids'] = $params['active_ids'];

}
 

if (isset($params['categories_active_ids'])) {
	 
	$tree['categories_active_ids'] = $params['categories_active_ids'];
	if(is_numeric($tree['categories_active_ids']) and isset($params['for-id']) and $params['for-id'] == 0){
	$all_parents = mw()->category_manager->get_parents($tree['categories_active_ids']);
	if(!empty($all_parents)){
		foreach($all_parents as $all_parent){
			if(intval($all_parent) != 0){
			$active_cats[] = 	$all_parent;
			}
		}
	}
	$active_cats[] = $tree['categories_active_ids'];
	$active_cats = array_unique($active_cats);
	$tree['categories_active_ids'] = $active_cats;
	}

} else if (!empty($active_cats1)) {
 	 // $tree['categories_active_ids'] = $active_cats1;

} else {


}


if (!empty($cats_ids)) {
	$cats_ids[] = $item1;
	// $tree['active_ids'] = $cats_ids;

}

$input_name = " name='parent' ";
if(isset($params['input-name'])){
	$input_name = " name='{$params['input-name']}' ";
}

$input_name_cats = "  ";
if(isset($params['input-name-categories'])){
	$input_name_cats = " name='{$params['input-name-categories']}' ";
}


$input_type_cats = "  type='checkbox'  ";
if(isset($params['input-type-categories'])){
	$input_type_cats = " type='{$params['input-type-categories']}' ";
}

$tree['active_code'] = 'checked="checked" ';
$tree['active_code'] = 'checked="checked" ';

$tree['link'] = "<label class='mw-ui-check'><input type='radio' {$input_name}  {active_code} value='{id}'   class='mw-ui-check-input-check' ><span></span><span>{title}</span></label>";
$tree['categores_link'] = "<label class='mw-ui-check'><input {$input_type_cats}  {$input_name_cats}   {active_code} value='{id}'   class='mw-ui-check-input-sel' ><span></span><span>{title}</span></label>";
if(isset($params['show_edit_categories_admin_link'])){
// $tree['categores_link'] = "<label class='mw-ui-check'><input {$input_type_cats}  {$input_name_cats}   {active_code} value='{id}'   class='mw-ui-check-input-sel' ><span></span><span>{title} "."<span title='Edit' onclick=\"event.stopPropagation();mw.url.windowHashParam('action', 'editcategory:{id}');return false;\" class='mw-icon-pen category-tree-icon-category-editable-hover-crtl'  ></span>"."</span></label>";
}


 
if (isset($params['is_shop']) and trim($params['is_shop']) =='y') {
 } else {
$tree['is_shop'] = 0;
}
if(isset($tree['is_shop'] )){

	unset($tree['is_shop'] );
}
 
if(isset($params['content_type']) and $params['content_type'] == 'product'){

	$tree['is_shop'] = 1;
}
if(isset($params['subtype']) and $params['subtype'] == 'product'){

	$tree['is_shop'] = 1;
}

if(isset($params['subtype']) and $params['subtype'] == 'post'){
   $tree['subtype'] = 'dynamic';
   if(isset($tree['is_shop'] )){

	unset($tree['is_shop'] );
}
} 
if (isset($params['active_ids'])) {
	 
//$active_cats[] = $tree['active_ids'];

}

if (isset($params['categories_removed_ids'])) {
	 $tree['categories_removed_ids'] = $params['categories_removed_ids'];

}
 if (isset($tree['subtype'])) {
	 
unset($tree['subtype']);
}
     $tree['is_active'] = 1;
pages_tree($tree);
?>
<?php endif; ?>
<?php  if(isset($params['include_global_categories']) and $params['include_global_categories'] == true  and isset($params['include_global_categories'])){

	$str0 = 'table=categories&limit=1000&data_type=category&' . 'parent_id=0&rel_id=0&rel=content';
 
	$fors = db_get($str0);

	if ($fors != false and is_array($fors) and !empty($fors)) {
		foreach ($fors as $cat) {
			$cat_params =$params;
			$pt_opts = array();
			$pt_opts['link'] = "<label class='mw-ui-check'><input type='checkbox'  {active_code} value='{id}' class='mw_cat_selector_{$rand}' ><span></span><span>{title}</span></label>";

			$pt_opts['parent'] =$cat['id'];
			$pt_opts['include_first'] = 1;
		}
	}

}



?>
<?php $cats_str = implode(',', $active_cats); ?>

<input type="hidden" name="<?php print $params['field-name']; ?>" id="mw_cat_selected_for_post" value="<?php print $cats_str ?>" />

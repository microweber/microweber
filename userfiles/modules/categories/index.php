<?php
if(isset($params['class'])){
unset($params['class']);
}

if(!isset($params['ul_class'])){
	 $params['ul_class'] = 'nav nav-list';
 }
    $params['rel'] = 'content';
	//  $params['rel_id'] = 'content';
	$category_tree_parent_page =  get_option('data-content-id', $params['id']);
	
	if($category_tree_parent_page != false and $category_tree_parent_page != '' and $category_tree_parent_page != 0){
	  $params['rel_id'] = 	$params['content_id'] = $category_tree_parent_page	;
	
	} else {
		
		
	}
	

category_tree($params); ?>

<?

/**
 * Print the site pages as tree
 *
 * @param string append_to_link 
 *        	You can pass any string to be appended to all pages urls
 * @param string link 
 *        	Replace the link href with your own. Ex: link="<? print site_url('page_id:{id}'); ?>"
 * @return string prints the site tree
 * @uses pages_tree($params);
 * @example  <module type="pages_menu" append_to_link="/editmode:y" />
 */
 
  
if(!isset($params['link'])){
	if(isset($params['append_to_link'])){
		$append_to_link = $params['append_to_link'];
	} else {
		$append_to_link = '';
	}
	 
	$params['link'] = '<a data-page-id="{id}" class="pages_tree_link {nest_level}" href="{link}'.$append_to_link.'">{title}</a>';
	
} else {
	
	$params['link'] = '<a data-page-id="{id}" class="pages_tree_link {nest_level}"  href="'.$params['link'].'">{title}</a>';
}


if (isset($params['data-parent'])) {
     $params['parent'] = intval($params['parent']);
} else {
    
	 $o = option_get('data-parent', $params['id']);
	 if($o != false and intval($o) >0){
		 $params['parent'] =  $o;
	 }
}

if (isset($params['data-include_categories'])) {
     $params['include_categories'] = intval($params['parent']);
} else {
    
	 $o = option_get('data-include_categories', $params['id']);
	// d($o);
	 if($o != false and intval($o) >0){
		 $params['include_categories'] =  $o;
	 }
}


 //d($params);
 pages_tree($params) ?>

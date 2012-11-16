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


 //
 pages_tree($params);
 
 if(isset($params['include_categories']) and $params['include_categories'] == true  and isset($params['include_global_categories'])){
	 
						
						
					 
						$str0 = 'table=table_taxonomy&limit=1000&data_type=category&' . 'parent_id=0&to_table_id=0&to_table=table_content';
		$fors = get($str0);
					//d($fors );
					
					
					if ($fors != false and is_array($fors) and !empty($fors)) {
			foreach ($fors as $cat) {
				$cat_params =$params;
						$cat_params['parent'] =$cat['id'];
						//$cat_params['to_table'] = 'table_content';
					//	$cat_params['to_table_id'] = ' 0 ';
					// $cat_params['for'] = 'table_content';
				 $cat_params['include_first'] = 1;
					 //$cat_params['debug'] = 1;
					// d($cat_params);
						 category_tree($cat_params);
			}
		}
						
				
				
				 
					
					
 
	 
 }
 
 
 
  ?>

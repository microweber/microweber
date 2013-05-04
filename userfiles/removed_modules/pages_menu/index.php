<?

/**
 * Print the site pages as tree
 *
 * @param string append_to_link
 *        	You can pass any string to be appended to all pages urls
 * @param string link
 *        	Replace the link href with your own. Ex: link="<?php print site_url('page_id:{id}'); ?>"
 * @return string prints the site tree
 * @uses pages_tree($params);
 * @usage  type="pages" append_to_link="/editmode:y"
 */


if(!isset($params['link'])){
	if(isset($params['append_to_link'])){
		$append_to_link = $params['append_to_link'];
	} else {
		$append_to_link = '';
	}

	$params['link'] = '<a data-page-id="{id}" class="{active_class} {active_parent_class} pages_tree_link {nest_level} {exteded_classes}" href="{link}'.$append_to_link.'">{title}</a>';

} else {

	$params['link'] = '<a data-page-id="{id}" class="{active_class} {active_parent_class} pages_tree_link {nest_level} {exteded_classes}"  href="'.$params['link'].'">{title}</a>';
}


if (isset($params['data-parent'])) {
     $params['parent'] = intval($params['parent']);
} else {

	 $o = get_option('data-parent', $params['id']);
	 if($o != false and intval($o) >0){
		 $params['parent'] =  $o;
	 }
}

if (isset($params['data-include_categories'])) {
     $params['include_categories'] = intval($params['parent']);
} else {

	 $o = get_option('data-include_categories', $params['id']);
	// d($o);
	 if($o != false and intval($o) >0){
		 $params['include_categories'] =  $o;
	 }
}


 //
 //pages_tree($params);
$include_categories = false;
 if(isset($params['include_categories']) and $params['include_categories'] == true  and isset($params['include_global_categories'])){




						$str0 = 'table=categories&limit=1000&data_type=category&' . 'parent_id=0&rel_id=0&rel=content';
		$fors = get($str0);
					//d($fors );


					if ($fors != false and is_array($fors) and !empty($fors)) {
			foreach ($fors as $cat) {
				$cat_params =$params;
						$cat_params['parent'] =$cat['id'];
						//$cat_params['rel'] = 'content';
					//	$cat_params['rel_id'] = ' 0 ';
					// $cat_params['for'] = 'content';
				 $cat_params['include_first'] = 1;
					 //$cat_params['debug'] = 1;
					// d($cat_params);
					$include_categories = 1;
						// category_tree($cat_params);
			}
		}








 }


 $module_template = get_option('data-template',$params['id']);
				if($module_template == false and isset($params['template'])){
					$module_template =$params['template'];
				}
				
				
				
				
				
				if($module_template != false){
						$template_file = module_templates( $config['module'], $module_template);
				
				} else {
						$template_file = module_templates( $config['module'], 'default');
				
				}
				
				//d($module_template );
				if(isset($template_file) and is_file($template_file) != false){
					include($template_file);
				} else {
					
						$template_file = module_templates( $config['module'], 'default');
				include($template_file);
					//print 'No default template for '.  $config['module'] .' is found';
				}
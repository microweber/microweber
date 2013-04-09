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





?>

<?php
 
d($params);
 if(!isset($params['active_ids'])){
	$params['active_ids'] = 0; 
 }
 
 
  if(!isset($params['input-name'])){
$params['input-name'] = 'category_id' ;
 }
 
  $posts_parent_page = intval($params['active_ids']) ;
  
 // d( $posts_parent_page);
  ?>

<select name="<?php print $params['input-name'] ?>"     >
  <option     <?php if((0 == intval($posts_parent_page))): ?>   selected="selected"  <?php endif; ?>><?php _e("None"); ?></option>
  <?php
$pt_opts = array();
$pt_opts['link'] = "{title}";
$pt_opts['list_tag'] = " ";
$pt_opts['list_item_tag'] = "option";
$pt_opts['include_categories'] = "true";
$pt_opts['for'] = "content";

 $pt_opts['categories_active_ids'] = $posts_parent_page;
 $pt_opts['remove_ids'] = $params['id'];

$pt_opts['active_code_tag'] = '   selected="selected"  ';
 

 

 mw('content')->pages_tree($pt_opts);


  ?>
  <?php
   
 if(isset($params['include_global_categories']) and $params['include_global_categories'] == true  and isset($params['include_global_categories'])){
	 
						
						
					 
						$str0 = 'table=categories&limit=1000&data_type=category&' . 'parent_id=0&rel_id=0&rel=content';
		$fors = mw('db')->get($str0);
					//d($fors );
					
					
					if ($fors != false and is_array($fors) and !empty($fors)) {
			foreach ($fors as $cat) {
				$cat_params =$params;
				$pt_opts = array();
$pt_opts['link'] = "{title}";
$pt_opts['list_tag'] = " ";
$pt_opts['list_item_tag'] = "option";
 
						$pt_opts['parent'] =$cat['id'];
						//$cat_params['rel'] = 'content';
					//	$cat_params['rel_id'] = ' 0 ';
					// $cat_params['for'] = 'content';
				 $pt_opts['include_first'] = 1;
					 //$cat_params['debug'] = 1;
					// d($cat_params);
						 mw('category')->tree($pt_opts);
			}
		}
						
				
				
				 
					
					
 
	 
 }
  
  
   ?>
</select>

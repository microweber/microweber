<?
 
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

<select name="<? print $params['input-name'] ?>"     >
  <option     <? if((0 == intval($posts_parent_page))): ?>   selected="selected"  <? endif; ?>>None</option>
  <?
$pt_opts = array();
$pt_opts['link'] = "{title}";
$pt_opts['list_tag'] = " ";
$pt_opts['list_item_tag'] = "option";
$pt_opts['include_categories'] = "true";
$pt_opts['for'] = "table_content";

 $pt_opts['categories_active_ids'] = $posts_parent_page;
 $pt_opts['remove_ids'] = $params['id'];

$pt_opts['active_code_tag'] = '   selected="selected"  ';
 

 

 pages_tree($pt_opts);


  ?>
  <?
   
 if(isset($params['include_global_categories']) and $params['include_global_categories'] == true  and isset($params['include_global_categories'])){
	 
						
						
					 
						$str0 = 'table=table_taxonomy&limit=1000&data_type=category&' . 'parent_id=0&to_table_id=0&to_table=table_content';
		$fors = get($str0);
					//d($fors );
					
					
					if ($fors != false and is_array($fors) and !empty($fors)) {
			foreach ($fors as $cat) {
				$cat_params =$params;
				$pt_opts = array();
$pt_opts['link'] = "{title}";
$pt_opts['list_tag'] = " ";
$pt_opts['list_item_tag'] = "option";
 
						$pt_opts['parent'] =$cat['id'];
						//$cat_params['to_table'] = 'table_content';
					//	$cat_params['to_table_id'] = ' 0 ';
					// $cat_params['for'] = 'table_content';
				 $pt_opts['include_first'] = 1;
					 //$cat_params['debug'] = 1;
					// d($cat_params);
						 category_tree($pt_opts);
			}
		}
						
				
				
				 
					
					
 
	 
 }
  
  
   ?>
</select>

 <?
 
// d($params);
 if(!isset($params['active-id'])){
	$params['active-id'] = 0; 
 }
 
 
  if(!isset($params['input-name'])){
$params['input-name'] = 'category_id' ;
 }
 
  $posts_parent_page = intval($params['active-id']) ?>
<select name="<? print $params['input-name'] ?>"     >
  <option     <? if((0 == intval($posts_parent_page))): ?>   selected="selected"  <? endif; ?>>None</option>
  
   <?
$pt_opts = array();
$pt_opts['link'] = "{title}";
$pt_opts['list_tag'] = " ";
$pt_opts['list_item_tag'] = "option";
 $pt_opts['actve_ids'] = $posts_parent_page;
//$pt_opts['remove_ids'] = $params['id'];
 
 
$pt_opts['active_code_tag'] = '   selected="selected"  ';
 



 pages_tree($pt_opts);


  ?>
  
  
  
  
  
</select>
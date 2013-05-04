<?php 

 
if(isset($params['id']) != false){
	
	 print menu_tree($params['id']);
	 
	 
} else {



if(isset($params['name']) != false){
	$id = get_menu_id($params['name']) ;
	 if(isset($params['max_levels']) and intval($params['max_levels']) != 0){
		 $max = intval($params['max_levels']);
	 } else {
		 $max = false;
	 }
	print menu_tree($id, $max );
	}
	
	
	else {
	print menu_tree('main_menu' );
}
	
} 
?>
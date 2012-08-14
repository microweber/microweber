<? 

 d($params);
if(isset($params['id']) != false){
	
	 menu_tree($params['id']);
	 
	 
} else {



if(isset($params['name']) != false){
	$id = get_menu_id($params['name']) ;
	 if(intval($params['max_levels']) != 0){
		 $max = intval($params['max_levels']);
	 } else {
		 $max = false;
	 }
	 menu_tree($id, $max );
	}
	
	
	else {
	 menu_tree('main_menu' );
}
	
} 
?>
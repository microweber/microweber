<? if($params['id'] != false){
	
	 menu_tree($params['id']);
	 
	 
} else {



if($params['name'] != false){
	
	
	$id =  CI::model('content')->getMenuIdByName($params['name']) ;
	
	 if(intval($params['max_levels']) != 0){
		 $max = intval($params['max_levels']);
	 } else {
		 $max = false;
	 }
		 menu_tree($id, $max );
	}
}
?>

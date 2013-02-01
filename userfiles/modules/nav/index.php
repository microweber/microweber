<? //include_once($config['path_to_module'].'functions.php'); ?>

<? 
if(isset($params['name'])){
	$params['menu-name'] = $params['name'];
	
}
  $menu_name = get_option('menu_name', $params['id']);  

if($menu_name != false){
	$params['menu-name'] = $menu_name;
}

if(isset($params['menu-name'])){
	
	$menu = get_menu('make_on_not_found=1&one=1&limit=1&title='.$params['menu-name']);
	if(isarr($menu)){
		
		$menu_filter =$params;
		$menu_filter['menu_id'] = intval($menu['id']);
		$mt =  menu_tree($menu_filter);
		if($mt != false){
			print ($mt);
		} else {
			pages_tree($params);
			//print "There are no items in the menu <b>".$params['menu-name']. '</b>';	
		}
	} else {
		pages_tree($params);
		//print "Click on settings to edit this menu";	
	}
	
} else {
	pages_tree($params);
	//print "Click on settings to edit this menu";	
}

 
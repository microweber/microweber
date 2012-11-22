<? 

  $menu_name = option_get('menu_name', $params['id']);  

if($menu_name != false){
	$params['menu-name'] = $menu_name;
}

if(isset($params['menu-name'])){
	
	$menu = get_menu('one=1&limit=1&title='.$params['menu-name']);
	if(isarr($menu)){
		$mt =  menu_tree($menu['id']);
		if($mt != false){
			print ($mt);
		} else {
			print "There are no items in the menu <b>".$params['menu-name']. '</b>';	
		}
	} else {
		print "Click on settings to edit this menu";	
	}
	
} else {
	print "Click on settings to edit this menu";	
}


 ?>
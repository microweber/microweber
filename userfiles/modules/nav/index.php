<? 

  $menu_name = option_get('menu_name', $params['id']);  

if($menu_name != false){
	$params['menu-name'] = $menu_name;
}

if(isset($params['menu-name'])){
	
	$menu = get_menu('one=1&limit=1&title='.$params['menu-name']);
	if(isarr($menu)){
		print menu_tree($menu['id']);
	} else {
		print "Click on settings to edit this menu";	
	}
	
} else {
	print "Click on settings to edit this menu";	
}


 ?>
<? 
if(isset($params['menu-name'])){
	
	$menu = get_menu('one=1&limit=1&title='.$params['menu-name']);
	if(isarr($menu)){
		print menu_tree($menu['id']);
	}
	
}


 ?>
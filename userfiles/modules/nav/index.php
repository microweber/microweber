<? //include_once($config['path_to_module'].'functions.php'); ?>


<style type="text/css">
    .module-nav li{
      list-style: none;
    }

</style>

<div class="mw-vspace"> </div>
<div class="navbar navbar-static">
<div class="navbar-inner">

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

		if(!isset($params['ul_class'])){
			$menu_filter['ul_class'] = 'nav nav-pills';
		}

		$menu_filter['menu_id'] = intval($menu['id']);
		$mt =  menu_tree($menu_filter);
		if($mt != false){
			print ($mt);
		} else {
			mw_notif("There are no items in the menu <b>".$params['menu-name']. '</b>');
			//pages_tree($params);
			//print "There are no items in the menu <b>".$params['menu-name']. '</b>';
		}
	} else {
		//pages_tree($params);
		   mw_notif("Click on settings to edit this menu");
	}

} else {
	//pages_tree($params);
	 mw_notif("Click on settings to edit this menu");
}

?>


</div>
</div>

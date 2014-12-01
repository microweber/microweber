<?php

/*

type: layout

name: Pills

description: Pills Navigation

*/

?>
<style>

.pillnavigattion{
  padding: 12px;
  background: rgba(10, 0, 0, 0.5);
  color:
}
.pillnavigattion a{
  color: white;
}

.pillnavigattion a:hover,
.pillnavigattion a:focus{
  color: #555;
} 

</style>
<div class="pillnavigattion">
<div class="navbar-collapse">
<?php
$menu_filter['li_class'] = 'nav-pills';
$menu_filter['ul_class'] = ' nav nav-pills ';
$menu_filter['ul_class_deep'] = 'dropdown-menu';
$menu_filter['li_class_deep'] = 'dropdown-submenu';
$menu_filter['li_class_empty'] = ' ';
$mt =  menu_tree($menu_filter);
if($mt != false){
	print ($mt);
} else {
	print lnotif("There are no items in the menu <b>".$params['menu-name']. '</b>');
}
?>
</div>
</div>
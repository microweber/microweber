<?php

/*

type: layout

name: Navbar

description: Navigation bar

*/

  //$template_file = false; ?>

<div class="navbar navbar-static  navbar-default ">
  <div class="navbar-inner">
    <div class="nav-container">
      	<?php

  $menu_filter['ul_class_deep'] = 'dropdown dropdown-menu';
	$menu_filter['li_class_deep'] = 'dropdown dropdown-submenu';
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
</div>

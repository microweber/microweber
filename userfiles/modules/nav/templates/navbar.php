<?php

/*

type: layout

name: Navbar

description: Navigation bar

*/

  //$template_file = false; ?>

<div class="navbar navbar-static">
  <div class="navbar-inner">
    <div class="container">
      	<?

        $menu_filter['ul_class_deep'] = 'dropdown-menu';
		$menu_filter['li_class_deep'] = 'dropdown-submenu';


		$mt =  menu_tree($menu_filter);

		if($mt != false){
		    print ($mt);
		} else {
		    mw_notif("There are no items in the menu <b>".$params['menu-name']. '</b>');
		}
   		?>
    </div>
  </div>
</div>

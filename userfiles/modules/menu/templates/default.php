<?php

/*

type: layout

name: Default

description: Default bar

*/

  //$template_file = false; ?>

<div class="navbar navbar-static">
  <div class="navbar-inner">
    <div class="container">
      	<?php

  $menu_filter['ul_class_deep'] = 'dropdown-menu';
	$menu_filter['li_class_deep'] = 'dropdown-submenu';
  $menu_filter['li_class_empty'] = ' ';

		$mt =  menu_tree($menu_filter);

		if($mt != false){
		    print ($mt);
		} else {
		    mw('format')->lnotif("There are no items in the menu <b>".$params['menu-name']. '</b>');
		}
   		?>
    </div>
  </div>
</div>

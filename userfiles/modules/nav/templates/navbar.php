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

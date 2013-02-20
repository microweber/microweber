<?php

/*

type: layout

name: Pills

description: Pills Navigation

*/

  //$template_file = false; ?>
 
      	<?
		$menu_filter['ul_class'] = 'nav nav-pills';
		$mt =  menu_tree($menu_filter);
		if($mt != false){
		print ($mt);
		} else {
		mw_notif("There are no items in the menu <b>".$params['menu-name']. '</b>');
		}
   		?>
 

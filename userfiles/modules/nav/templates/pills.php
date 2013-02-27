<?php

/*

type: layout

name: Pills

description: Pills Navigation

*/

  //$template_file = false; ?>

      	<?
      	 //$menu_filter['ul_tag'] = 'div';
		 //$menu_filter['li_tag'] = 'span';
 		$menu_filter['ul_class'] = 'nav nav-pills';
		$menu_filter['ul_class_deep'] = 'dropdown-menu';
		$menu_filter['li_class_deep'] = 'dropdown-submenu';
		$menu_filter['li_class_empty'] = '';
		$mt =  menu_tree($menu_filter);
		if($mt != false){
		    print ($mt);
		} else {
		    mw_notif("There are no items in the menu <b>".$params['menu-name']. '</b>');
		}
   		?>


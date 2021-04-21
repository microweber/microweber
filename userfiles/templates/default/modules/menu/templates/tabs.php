<?php

/*

type: layout

name: Tabs

description: Tabs menu

*/

  //$template_file = false; ?>
  <?php
        $menu_filter['ul_class'] = 'nav nav-tabs';
		$menu_filter['ul_class_deep'] = 'dropdown-menu';
		$menu_filter['li_class_deep'] = 'dropdown-submenu';
  $mt =  menu_tree($menu_filter);
  if($mt != false){
			print ($mt);
		} else {
			print lnotif("There are no items in the menu <b>".$params['menu-name']. '</b>');
			//pages_tree($params);
			//print "There are no items in the menu <b>".$params['menu-name']. '</b>';
		}
   ?>
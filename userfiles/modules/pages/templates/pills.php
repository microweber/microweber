<?php

/*

type: layout

name: Pills

description: Pills Navigation

*/


$menu_filter['li_class'] = 'nav-pills';
$menu_filter['ul_class'] = 'nav nav-pills';
$menu_filter['ul_class_deep'] = 'dropdown-menu';
$menu_filter['li_class_deep'] = 'dropdown-submenu';
$menu_filter['li_class_empty'] = ' ';
$mt =  menu_tree($menu_filter);
if($mt != false){
	print ($mt);
} else {
	print lnotif("There are no items in the menu <b>".$params['menu-name']. '</b>');
}

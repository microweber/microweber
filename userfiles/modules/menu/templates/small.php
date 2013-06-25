<?php

/*

type: layout

name: Small

description: Small Navigation

*/


$menu_filter['ul_class'] = 'nav nav-lite';
$menu_filter['maxdepth'] = 1;

$menu_filter['li_class_empty'] = ' ';
$mt =  menu_tree($menu_filter);
if($mt != false){
	print ($mt);
} else {
	mw_notif_live_edit("There are no items in the menu <b>".$params['menu-name']. '</b>');
}

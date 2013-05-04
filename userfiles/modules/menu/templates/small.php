<?php

/*

type: layout

name: Small

description: Small Navigation

*/


      	 //$menu_filter['ul_tag'] = 'div';
		 //$menu_filter['li_tag'] = 'span';
		 //


$menu_filter['ul_class'] = 'nav nav-small';

$menu_filter['li_class_empty'] = ' ';
$mt =  menu_tree($menu_filter);
if($mt != false){
	print ($mt);
} else {
	mw_notif_live_edit("There are no items in the menu <b>".$params['menu-name']. '</b>');
}

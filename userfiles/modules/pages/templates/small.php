<?php

/*

type: layout

name: Small

description: Small Navigation

*/


$menu_filter['ul_class'] = 'module-pages-menu module-pages-menu-small';
$menu_filter['maxdepth'] = 1;

$menu_filter['li_class_empty'] = ' ';
$mt =  menu_tree($menu_filter);
if($mt != false){
	print ($mt);
} else {
	print lnotif(_e('There are no items in the menu', true) . " <b>".$params['menu-name']. '</b>');
}

?>
<script>mw.require("<?php print $config['url_to_module'] ?>templates/templates.css", true);</script>



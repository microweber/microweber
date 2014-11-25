<?php

/*

type: layout

name: MEGAMENU

description: MEGAMENU



 Navigation

*/


  $menu_filter['ul_class'] = 'nav-small';
  $menu_filter['maxdepth'] = 1;
  $menu_filter['li_class_empty'] = ' ';
  $mt =  menu_tree($menu_filter);
  if($mt != false){
  	print ($mt);
  }
  else {
  	print lnotif("There are no items in the menu <b>".$params['menu-name']. '</b>');
  }
?>
<h1>MEGA</h1>
<script>mw.moduleCSS("<?php print $config['url_to_module']; ?>style.css");</script>
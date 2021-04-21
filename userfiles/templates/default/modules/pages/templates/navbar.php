<?php

/*

type: layout

name: Navbar

description: Navigation bar

*/

  //$template_file = false; ?>

  <script>mw.require("<?php print $config['url_to_module'] ?>templates/templates.css", true);</script>

<div class="well module-pages-menu module-pages-menu-navbar">


    <?php
    $menu_filter['ul_class'] = 'nav nav-pills';
	$menu_filter['ul_class_deep'] = 'nav nav-pills ';
    $menu_filter['li_class_empty'] = ' ';
?>
      	<?php


		$mt =  menu_tree($menu_filter);

		if($mt != false){
		    print ($mt);
		} else {
		    print lnotif("There are no items in the menu <b>".$params['menu-name']. '</b>');
		}
   		?>

</div>

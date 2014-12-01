<?php

/*

type: layout

name: Default

description: Default Menu skin

*/

?>

<script>mw.moduleCSS("<?php print $config['url_to_module']; ?>style.css", true);</script>



<div class="shopmagmenu">
      <?php
      $menu_filter['ul_class'] = 'mw-ui-navigation mw-ui-navigation-horizontal';
      $menu_filter['ul_class_deep'] = '';
      $mt =  menu_tree($menu_filter);
      if($mt != false){
        print ($mt);
      }
      else {
        print lnotif("There are no items in the menu <b>".$params['menu-name']. '</b>');
      }
      ?>
</div>

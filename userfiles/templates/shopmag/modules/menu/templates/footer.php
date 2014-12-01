<?php

/*

type: layout

name: Footer

description: Footer Menu skin

*/

?>




      <?php
      $menu_filter['ul_class'] = 'mw-footer-menu';
      $menu_filter['ul_class_deep'] = '';
      $mt =  menu_tree($menu_filter);
      if($mt != false){
        print ($mt);
      }
      else {
        print lnotif("There are no items in the menu <b>".$params['menu-name']. '</b>');
      }
      ?>


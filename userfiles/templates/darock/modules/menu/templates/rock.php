<?php

/*

type: layout

name: Rock

description: Rock Menu skin

*/

?>




<div class="module-navigation module-navigation-rock">
      <?php
      $menu_filter['ul_class'] = '';
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

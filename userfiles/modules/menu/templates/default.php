<?php

/*

type: layout

name: Default

description: Default Menu skin

*/

?>

<script>mw.moduleCSS("<?php print $config['url_to_module']; ?>style.css", true);</script>



<div class="module-navigation module-navigation-default">
      <?php
      $mt =  menu_tree($menu_filter);
      if($mt != false){
        print ($mt);
      }
      else {
        print lnotif(_e('There are no items in the menu', true) . " <b>".$params['menu-name']. '</b>');
      }
      ?>
</div>

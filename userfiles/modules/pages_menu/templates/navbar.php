<?php

/*

type: layout

name: Navbar

description: Navigation bar

*/

  //$template_file = false; ?>

<div class="navbar navbar-static">
  <div class="navbar-inner">
    <div class="container">
      	 <?  pages_tree($params);  ?>
  <? if($include_categories != false):  ?><? category_tree($cat_params); ?><? endif; ?>
    </div>
  </div>
</div>

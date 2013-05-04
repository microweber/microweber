<?php

/*

type: layout

name: Default

description: Default menu

*/

  //$template_file = false; ?>
  <?php  pages_tree($params);  ?>
  <?php if($include_categories != false):  ?><?php category_tree($cat_params); ?><?php endif; ?>
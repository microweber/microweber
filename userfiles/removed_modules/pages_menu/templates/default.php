<?php

/*

type: layout

name: Default

description: Default menu

*/

  //$template_file = false; ?>
  <?  pages_tree($params);  ?>
  <? if($include_categories != false):  ?><? category_tree($cat_params); ?><? endif; ?>
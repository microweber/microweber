<?php

/*

type: layout

name: List

description: List Navigation

*/

  //$template_file = false; ?>
  
  <?
    $params['ul_class'] = 'nav nav-list';
  ?>
 <?  pages_tree($params);  ?>

  <? if($include_categories != false):  ?><? $cat_params['ul_class'] = 'nav nav-list'; category_tree($cat_params); ?><? endif; ?>

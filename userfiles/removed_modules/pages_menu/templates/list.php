<?php

/*

type: layout

name: List

description: List Navigation

*/

  //$template_file = false; ?>
  
  <?php
    $params['ul_class'] = 'nav nav-list';
  ?>
 <?php  pages_tree($params);  ?>

  <?php if($include_categories != false):  ?><?php $cat_params['ul_class'] = 'nav nav-list'; category_tree($cat_params); ?><?php endif; ?>

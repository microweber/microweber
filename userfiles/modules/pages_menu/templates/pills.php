<?php

/*

type: layout

name: Pills

description: Pills Navigation

*/

  //$template_file = false; ?>
  
      	<?
		$params['ul_class'] = 'nav nav-pills nav-stacked';
		$params['ul_class'] = 'nav nav-tabs nav-stacked';
		$params['ul_class'] = 'nav nav-list';

   		?>
 <?  pages_tree($params);  ?>

  <? if($include_categories != false):  ?><? $cat_params['ul_class'] = 'nav nav-pills'; category_tree($cat_params); ?><? endif; ?>

<?php

/*

type: layout

name:  layout

description: layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>
<?  if(stristr(url(), 'directory') or stristr(url(), 'stuff2study')): ?>
<? $btn_use = false; ?>
<? endif; ?>
<?php 
  
   $get_categories_params = array(); 
   $get_categories_params['parent'] = $page['content_subtype_value']; 
	$categories = get_categories($get_categories_params) ;
  
 
 if($btn_use == false){
	 
	$btn_use = 'seethis_offer_but2.jpg'; 
 }
 
  ?>

<div class="money_container">
  <? // if($skip_user_check != true and stristr($user_data['email'], 'gmail')): ?>
  <? if($skip_user_check != true): ?>
  <div class="money_page_tit"><? print $page['content_title']; ?></div>
<!--  <div class="money2_page_text"> Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. </div>
-->  <? $user = user_id(); ?>
  <? if($user > 0): ?>
  <? require TEMPLATE_DIR. "blocks/category_scroll.php"; ?>
  <? $c = url_param('categories');  ?>
  <?   // p( $c ); ?>
  <?  if(stristr($user_data['email'], 'ac.uk') or stristr($user_data['is_admin'], 'y')): ?>
 
  <? if($c == false and $mid_posts == false and $post  == false): ?>
  <? include    "categories_list_mid.php"; ?>
  <? else: ?>
  <? include    "posts_list.php"; ?>
  <? endif; ?>
  <? else: ?>
  <? include   TEMPLATE_DIR.  "must_reg.php"; ?>
  <? endif;  ?>
  <? else:  ?>
  <? include   TEMPLATE_DIR.  "must_reg.php"; ?>
  <? endif;  ?>
  <? else : ?>
  <? $c = url_param('categories');  ?>
 
  <? if($c == false and $mid_posts == false): ?>
  <? include    "categories_list_mid.php"; ?>
  <? else: ?>
  <? include    "posts_list.php"; ?>
  <? endif; ?>
  <? endif; ?>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>

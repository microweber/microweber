
<?
$category_params = array();
$category_params['parent_id'] = $category;
$categories = get_categories($category_params);

//p($categories);

?>
<? foreach($categories as $cat): ?>
<?php  if(intval($cat['parent_id']) > 0): ?>
<?php  $cat['id'] = intval($cat['id']); 
if( $cat['id'] != 0): ?>
<?php 
 // p($cat['id']);
    $params= array();
	//$params['display']= 'games_list_single_item.php';
	$params['selected_categories'] = array($cat['id']);
	$params['items_per_page'] = 6;
	$if_posts = get_posts($params);
	$count = count($if_posts);

 
	if(!empty($if_posts) and $count > 0): ?>
<h1>Newest games from <? print $cat['taxonomy_value'] ?></h1>
<div class="top_games">

<? loop($if_posts, 'games_list_single_item.php', 'the_post') ; ?>

  
  <a class="mw_btn_s right" href="<? print get_category_url($cat['id']); ?>"><span>See all <? print get_category_items_count($cat['id']); ?> games</span></a> </div>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
<? endforeach; ?>
 

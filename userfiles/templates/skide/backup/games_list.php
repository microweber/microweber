
<div id="sidebar">    <? include(TEMPLATE_DIR.'banner_side.php')	; ?> </div>
<div id="wall">
  <?php if(count($active_categories) == 1): ?>
  <h1><? print $category['taxonomy_value'] ?></h1>
<!--  <div class="richtext">
    <p>Top games from all categories. Play most popular games now</p>
  </div>-->
 <!-- <div class="top_games top_games1"> </div>-->
  <? $categories = get_categories(); ?>
  <? foreach($categories as $category): ?>
  <h1>Most popular from <? print $category['taxonomy_value'] ?></h1>
  <div class="top_games">
    <?php 
    $params= array();
	$params['display']= 'games_list_single_item.php';
	$params['selected_categories'] = array($category['id']);
	$params['items_per_page'] = 5;
	$params['curent_page'] = 1;
	get_posts($params);
	?>
    <a class="mw_btn_s right" href="<? print get_category_url($category['id']); ?>"><span>See all <? print get_category_items_count($category['id']); ?> games</span></a> </div>
  <? endforeach; ?>
<?php else: ?>

 <h1><? print $category['taxonomy_value'] ?></h1>
 
  <div class="top_games">
    <?php 
    $params= array();
	$params['display']= 'games_list_single_item.php';
	get_posts($params);
	?>
    
</div>
 <?php endif; ?>

 <? paging($display = 'default') ?>
   
</div>

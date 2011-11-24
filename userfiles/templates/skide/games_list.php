
<div id="sidebar">
  <? include(TEMPLATE_DIR.'banner_side.php')	; ?>
  <br />
  <br />
  <br />
  <br />
  <?
$voted_by_user = false;
  $var_params= array();
 
 
 $var_params['selected_categories'] =  array(6);
$var_params['fields'] =  array('id');
$var_params['items_per_page'] = 4;
$var_params['curent_page'] = rand(1,5);
 $games =  get_posts($var_params);
 
 $games = $games['posts'];

 
 @shuffle( $games);
 ?>
  <h2>New Toys</h2>
  <ul class="new-toys">
    <? foreach($games as $item):
   $item = get_post($item['id']);
   ?>
    <li> <a class="img" href="<? print post_link($item['id']); ?>"><img src="<? print thumbnail($item['id'], 120) ?>" alt="" /></a>
      <div class="toy_desc">
        <h4><a href="<? print post_link($item['id']); ?>"><? print $item['content_title']; ?></a></h4>
        <span><? print character_limiter(codeClean($item['content_body']), 100); ?></span> <strong class="red">$<? print $item["custom_fields"]['price'];?> </strong>
        <div class="c" style="padding-bottom: 5px;"></div>
        <a href="<? print post_link($item['id']); ?>" class="right">See more</a> </div>
    </li>
    <? endforeach; ?>
  </ul>
</div>
<div id="wall">
  <h1 id="results_holder_title" style="display:none" ></h1>
  <div class="top_games" id="results_holder" style="display:none"></div>
  <?php if(count($active_categories) == 1): ?>
  <h1 id="top_games_holder_title" ><? print $page['content_title'] ?></h1>
  <br />
  <div class="top_games" id="top_games_holder">
    <microweber module="posts/most_popular_from_categories" category="1" />
  </div>
  <?php else: ?>
  <?  $category = get_category($active_category);  ?>
  <h1 id="top_games_holder_title"><? print $category['taxonomy_value'] ?></h1>
  <div class="top_games" id="top_games_holder">
    <microweber module="posts/list" file="posts_list_games" category="<? print  $category['id'] ?>">
  </div>
  <? paging($display = 'divs') ?>
  <?php endif; ?>
</div>

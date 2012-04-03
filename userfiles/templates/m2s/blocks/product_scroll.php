<div class="products_container">
  <div class="prod_all_container">
    <? 
   $params1 = array();
   $params1['category'] = '100002047';
   $params1['limit'] = '4';
   
   $posts1 = get_posts( $params1); 
   
    ?>
    <? foreach($posts1['posts'] as $post1): ?>
    <div class="prodbox">
      <div class="prod_bg">
      <a class="prod_bg_img" href="<? print  post_link($post1['id']); ?>" style="background-image:url('<? print thumbnail($post1['id'], 190); ?>')"></div>
      </a>
      <a class="prod_text" href="<? print  post_link($post1['id']); ?>"><? print  $post1['content_title']; ?></a>
    </div>
    <? endforeach; ?>
  </div>
  <a href="<? print category_link('100002047'); ?>" id="selectall">See All from Shopping Center</a> </div>

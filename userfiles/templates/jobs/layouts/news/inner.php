
<div class="page_tit"><? print $post['content_title']; ?></div>
<div class="body_part_inner">
  <div class="body_left_inner">
  <h1><? print $post['content_title']; ?></h1>
  <br />

  
   <? print $post['the_content_body']; ?>
    <? $c = $this->taxonomy_model->getTaxonomiesForContent($post['id'], $taxonomy_type = 'categories');
		//print PAGE_ID;
		?>
    <?php
		   $param2 = array();
   $param2['page'] = PAGE_ID; 
  //$param2['debug'] = false; 
  $param2['categories'] = $c;
 	 $param2['limit'] = 10;
	$posts_temp =  get_posts($param2);  
  //  p($posts_temp);
  ?>
    <? if(!empty($posts_temp['posts'])): ?>
    <div class="news_links"> <strong>Other Medical and Health News </strong><br />
      <? foreach($posts_temp['posts'] as $post1): ?>
      <a href="<? print post_link($post1['id']) ?>"><? print $post1['content_title'] ?></a><br />
      <? endforeach; ?>
    </div>
    <? endif; ?>
    
    <microweber module="comments/default" post_id="<? print  $post['id'] ?>" />
     
    
  </div>
  <? include TEMPLATE_DIR. "layouts/news/sidebar.php"; ?>
</div>

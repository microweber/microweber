
<div class="page_tit"><? print $page['content_title'] ?></div>
<div class="body_part_inner">
  <div class="body_left_inner">
  
 
  
  <? foreach($posts as $post): ?>
  
      <div class="news_blk">
      <div class="news_thumb"><a href="<? print post_link($post['id']) ?>"><img src="<? print thumbnail($post['id'], 120) ?>" alt="news" /></a></div>
      <div class="news_content">
        <div class="news_head"><a href="<? print post_link($post['id']) ?>"><? print $post['content_title'] ?></a></div>
        <div class="news_text"><? print character_limiter($post['content_body_nohtml'], 150) ?> </div>
        <div class="news_readmore"><a href="<? print post_link($post['id']) ?>"><img src="<? print TEMPLATE_URL ?>images/readmore_but.jpg" alt="read more" border="0" /></a></div>
      </div>
    </div>
    
    
    
 
<? endforeach;  ?>



  

    
    
    
 

  
  
  <div class="pagination"><? paging('uls'); ?>
    </div>
  
  </div>
  <? include TEMPLATE_DIR. "layouts/news/sidebar.php"; ?>
</div>

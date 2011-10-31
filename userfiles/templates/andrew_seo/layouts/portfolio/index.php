<?php

/*

type: layout

name: portfolio

description: portfolio site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div id="subheader_container">
  <div id="subheader">
    <h2><? print $page['content_title']; ?></h2>
  </div>
</div>
<div id="content_wrapper">
  <!-- sidebar -->
 <? include TEMPLATE_DIR. "sidebar_page_portfolio.php"; ?>
    <!-- /sidebar box 1 -->
 
  <!-- /sidebar -->
  <!-- content -->
  <div id="content" >
    <!-- portfolio item 1 -->
   
   <? if($post): ?>
    <h3><a href="<? print post_link($post['id']); ?>">
      <?  print $post['content_title']; ?>
      </a></h3>
      
      <div class="portfolio-item" style="background-color:#FFF;"><a href="<? print post_link($post['id']); ?>">
      <?  $thumb = CI::model ( 'content' )->contentGetThumbnailForContentId ( $post ['id'], 600 ); ?>
      <img border="0" src="<? print $thumb ?>" alt="<?  print addslashes($post['content_title']); ?>" /> </a>
     
      </div>
       <br />
      <div class="portfolio-item" style="background-color:#FFF;">
      <div style="padding:25px;">
      

      <?  print $post['content_body']; ?>
      </div> </div>
        <br />
    
    
    
      <? include TEMPLATE_DIR. "footer_order_now.php"; ?>
      
   
   <? else: ?>
    <? //p($posts); ?>
    <? foreach($posts as $post): ?>
    <h3><a class="nodec14b" href="<? print post_link($post['id']); ?>">
      <?  print $post['content_title']; ?>
      </a></h3>
    <div class="portfolio-item"><a href="<? print post_link($post['id']); ?>">
      <?  $thumb = CI::model ( 'content' )->contentGetThumbnailForContentId ( $post ['id'], 600 ); ?>
      <img border="0" src="<? print $thumb ?>" alt="<?  print addslashes($post['content_title']); ?>" /> </a></div>
    <p>
      <?  print $post['content_description']; ?>
    </p>
    
    <hr />
    <? endforeach; ?>
    <? endif; ?>
    
    
    
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>

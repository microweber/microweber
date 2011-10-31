<?php

/*

type: layout

name: testimonials

description: testimonials site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div id="subheader_container">
  <div id="subheader">
    <h2><a href="<? print page_link($page['id']); ?>"><? print $page['content_title']; ?></a></h2>
  </div>
</div>
<div id="content_wrapper">
  <!-- sidebar -->
 <? include TEMPLATE_DIR. "sidebar_services.php"; ?>
    <!-- /sidebar box 1 -->
 
  <!-- /sidebar -->
  <!-- content -->
  <div id="content" >
    <!-- portfolio item 1 -->
   
   <? if($post): ?>

      
  
      <div class="portfolio-item" style="background-color:#FFF;">
      
      <div style="padding:25px;">
          <h3>
      <?  print $post['content_title']; ?>
      </h3>
      
       <?  $thumb = CI::model ( 'content' )->contentGetThumbnailForContentId ( $post ['id'], 'original' ); ?>
      <img border="0" src="<? print $thumb ?>" alt="<?  print addslashes($post['content_title']); ?>" />

      <?  print $post['content_body']; ?>
      </div> </div>
        <br />
    
    
    
  
      
   
   <? else: ?>
    <? //p($posts); ?>
    <? foreach($posts as $post): ?>
    
    
 
    
    
      <div class="content_right_box2">

   
    <br />
    <blockquote><a style="float:left; margin-right:10px;" href="<? print post_link($post['id']); ?>">
      <?  $thumb = CI::model ( 'content' )->contentGetThumbnailForContentId ( $post ['id'], 80 ); ?>
      <img border="0" src="<? print $thumb ?>" alt="<?  print addslashes($post['content_title']); ?>" /> </a> <a href="<? print post_link($post['id']); ?>" class="txtlink"><?  print $post['content_body_nohtml']; ?> </a></blockquote>
    <p id="quoteauthor">
    <span class="bold"> 
    <a class="nodec" href="<? print post_link($post['id']); ?>">
      <?  print $post['content_title']; ?>
      </a></span>
     </p>
  </div>
    
    
    
    
    
    
    
     
    <? endforeach; ?>
    <? endif; ?>
    
    
        <? include TEMPLATE_DIR. "footer_order_now.php"; ?>
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>

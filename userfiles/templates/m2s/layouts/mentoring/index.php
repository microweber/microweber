<?php

/*

type: layout

name:  layout

description: layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div class="inner_container">
  <div class="inner_container_top"></div>
  <div class="inner_container_mid">
    <div class="inner_left">
      <div class="howit_works_left_img"><img src="<? print TEMPLATE_URL ?>images/blog_left_img.jpg" width="210" height="172" /></div>
      <div class="blog_category_tit">Categories</div>
      <mw module="content/category_tree" include_first="0" content_parent="<? print $page['content_subtype_value'] ?>" />
      <!--<ul>
        <li><a href="#" id="current">Category1</a></li>
        <li><a href="#" id="left_link">Category2</a></li>
        <li><a href="#" id="left_link">Category3</a></li>
        <li><a href="#" id="left_link">Category4</a></li>
      </ul>-->
      <div class="sponsored_tit">&nbsp;</div>
      <div class="sponsor_logo"><img src="<? print TEMPLATE_URL ?>images/sponsor_logo.jpg" alt="sponsor" /></div>
    </div>
    <div class="inner_rt">
    
    
      <? if($post): ?>
    
    <? include     "inner.php"; ?>
    
    
    
    <? else: ?>
    
    
    
    <? foreach($posts as $post): ?>
      <?
  //$post_image_data = get_picture($post['id'], $for = 'post'); 
  //	$thumb = get_media_thumbnail($post_image_data['id'], 192);
  
  ?>
      <div class="agony_blk">
        <div class="blog_entry_top">
          <div class="blog_title"><? print $post['content_title'] ?></div>
          <div class="blog_comments">
            <div class="comments_number"><? print  comments_count($content_id = $post['id'], $is_moderated = false, $for = 'post'); ?></div>
            <div class="comments_lable">Comments</div>
          </div>
        </div>
        <div class="posted">Posted by <? print user_name($post['created_by']) ?> on <? print $post['created_on'] ?></div>
        <div class="blog_img"><a href="<? print post_link($post['id']) ?>"><img src="<? print thumbnail($post['id']); ?>" alt="blog" border="0" /></a></div>
        <div class="blog_text"> <? print   character_limiter( $post['content_description'], 350 , "...") ?> </div>
        <div class="blog_like"><img src="<? print TEMPLATE_URL ?>images/like_img.jpg" alt="like" width="140" height="21" /></div>
        <div class="blog_read_more"><a href="<? print post_link($post['id']) ?>"><img src="<? print TEMPLATE_URL ?>images/blog_read_more_but.jpg" alt="read more" border="0" /></a></div>
      </div>
      <? endforeach; ?>
     <? paging(); ?>
      
      
    
    <? endif; ?>
    
      
      
      
      
      
      
    </div>
  </div>
  <div class="inner_container_bot">
      <div class="fb-like" data-href="<? print url() ?>" data-send="true" data-width="450" data-show-faces="true" data-font="tahoma"></div>

    </div>
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>

<?php

/*

type: shop

name: shop layout

description: shop site layout









*/



?><? include TEMPLATE_DIR. "header.php"; ?>
<? //p($posts); ?>

<div class="wrap content_holder"  >

<table border="0" cellspacing="5" cellpadding="5">
  <tr valign="top">
    <td width="700">

  <? if(!empty($post)) : ?>
  <? include   "inner.php"; ?>
  <? else : ?>
  <? foreach ($posts as $post)  : ?>
  <div class="author-content2">
  <? $th = thumbnail($post['id'], 200); ?>
  <ul class="gallery">
	<li><a href="<? print  post_link($post['id']); ?>"><span></span><img src="<? print $th ?>" align="left" hspace="10" class="img_pad" border="0" /></a></li>
 
</ul>

  
  
    <h2 class="post_title"> <a href="<? print  post_link($post['id']); ?>"><? print  $post['content_title']; ?></a></h2>
    <br>

    <p><? print  character_limiter($post['content_body_nohtml'], 300); ?>
    </p>
    <br>

     <a href="<? print  post_link($post['id']); ?>" class="read_more_link">Купи</a> </div>
  <? endforeach; ?>
  <div class="paging">
  <span class="left">Страници:</span>
 
  <microweber module="content/paging">
  </div>
  <? endif;  ?></td>
    <td>
    
      <div class="right_sidebar">
    <microweber module="content/category_tree" from="1686"  content_parent="1686" include_first="true"  />
  </div>

 <div class="right_sidebar">
    
        <h4 class="post_title_small"> <a href="<? print category_link(1686); ?>">Книги</a></h4>

    
    <br />

 <microweber module="posts/list" file="posts_sidebar" category="1686" items_per_page="100"></microweber>

 
 
</div>

 

  

    </td>
  </tr>
</table>

 
  
  
  
  
  
</div>
<? include TEMPLATE_DIR. "footer.php"; ?>

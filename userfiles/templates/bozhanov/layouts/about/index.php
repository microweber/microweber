<?php

/*

type: blog

name: blog layout

description: blog site layout









*/



?><? include TEMPLATE_DIR. "header.php"; ?>
<? //p($posts); ?>

<div class="wrap content_holder"  >

<table border="0" cellspacing="5" cellpadding="5">
  <tr valign="top">
    <td width="700">
<? $post = $page; ?>
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

     <a href="<? print  post_link($post['id']); ?>" class="read_more_link">Прочети повече</a> </div>
  <? endforeach; ?>
  <div class="paging">
  <span class="left">Страници:</span>
 
  <microweber module="content/paging">
  </div>
  <? endif;  ?></td>
    <td>
    
    <div class="right_sidebar">
    
        <h4 class="post_title_small"> <a href="https://www.facebook.com/dbozhanov">Да бъдем приятели!</a></h4>

    
    <br />

    
   <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="fb-like-box" data-href="https://www.facebook.com/dbozhanov" data-width="292" data-show-faces="true" data-stream="true" data-header="true"></div>
    </div>
    
    <br /> 
    
    
    
    
    <? include TEMPLATE_DIR. "buy_book_sidebar.php"; ?>
    

 <div class="right_sidebar">
    
        <h4 class="post_title_small"> <a href="<? print category_link(1684); ?>">На живо</a></h4>

    
    <br />

 <microweber module="posts/list" file="posts_sidebar" category="1684" items_per_page="100"></microweber>

 
 
</div>

 

 <div class="right_sidebar">
    
        <h4 class="post_title_small"> <a href="<? print category_link(1683); ?>">Блог</a></h4>

    
    <br />

 <microweber module="posts/list" file="posts_sidebar" category="1683" items_per_page="100"></microweber>

  <microweber module="posts/list" file="posts_sidebar" category="1685" items_per_page="100"></microweber>
 
</div>


    </td>
  </tr>
</table>

 
  
  
  
  
  
</div>
<? include TEMPLATE_DIR. "footer.php"; ?>

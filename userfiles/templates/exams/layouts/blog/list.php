<div class="posts-list-blog" >
  <? foreach($posts['posts'] as $post): ?>
  <div itemscope itemtype="http://schema.org/Article" class="single-post"> 
    <div class="single-post-description"  itemprop="description"> 
    
    
    
    <a itemprop="url" class="img2" href="<? print post_link($post['id']); ?>"><img align="left" src="<? print thumbnail($post['id'], $tn_s) ?>" /></a>
     <a  class="post-title-big"  href="<? print post_link($post['id']); ?>"  itemprop="name"><? print $post['content_title'] ?></a> 

 <? print character_limiter($post['content_description'], 2200);  ?> 
 <br />

    <a itemprop="url" href="<? print post_link($post['id']); ?>" class="single-post-more-link">Read more...</a> </div>
     </div>
   <br />

  <? endforeach; ?>
</div>

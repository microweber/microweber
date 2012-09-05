<ul class="posts-list" >
  <? foreach($posts['posts'] as $post): ?>
  <li itemscope itemtype="http://schema.org/Article" class="single-post"> 
     
  
    <p class="single-post-description2" itemprop="description">
	  <a itemprop="url" href="<? print post_link($post['id']); ?>" class="img" style="background-image: url('<? print thumbnail($post['id'], $tn_s) ?>')"><span></span></a>
	 <a  class="post-title-small"  href="<? print post_link($post['id']); ?>"  itemprop="name"><? print $post['content_title'] ?></a>
     <br />

	<? print character_limiter($post['content_description'], 220);  ?></p>
    
    <!--
    <a itemprop="url" href="<? print post_link($post['id']); ?>" class="single-post-more-link">Read more</a>--> </li>
  <? endforeach; ?>
</ul>

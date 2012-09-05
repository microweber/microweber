<ul class="posts-list">
  <? foreach($posts['posts'] as $post): ?>
  <li itemscope itemtype="http://schema.org/Article" class="single-post"> <strong ><a  class="post-title"  href="<? print post_link($post['id']); ?>"  itemprop="name"><? print $post['content_title'] ?></a></strong>
     
  
    <p class="single-post-description" itemprop="description">
	  <a itemprop="url" href="<? print post_link($post['id']); ?>" class="img" style="background-image: url('<? print thumbnail($post['id'], $tn_s) ?>')"><span></span></a>
	
	<? print $post['content_description'];  ?></p>
    
    <!--
    <a itemprop="url" href="<? print post_link($post['id']); ?>" class="single-post-more-link">Read more</a>--> </li>
  <? endforeach; ?>
</ul>

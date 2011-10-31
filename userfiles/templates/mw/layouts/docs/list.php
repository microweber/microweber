<ul class="posts-list">
  <? foreach($posts['posts'] as $post): ?>
  <li itemscope itemtype="http://schema.org/Article" class="single-post"> <strong ><a  class="post-title"  href="<? print post_link($post['id']); ?>"  itemprop="name"><? print $post['content_title'] ?></a></strong>
    <div class="single-post-info"> <span class="single-post-author">by <span itemprop="author"><? print user_name($post['created_by']); ?></span></span> | <span class="single-post-date">published on <span itemprop="datePublished"><? print ($post['created_on']); ?></span></span> </div>
       <p class="single-post-description" itemprop="description"><? print $post['content_description'];  ?></p>
    <a itemprop="url" href="<? print post_link($post['id']); ?>" class="single-post-more-link">Read more</a> </li>
  <? endforeach; ?>
</ul>
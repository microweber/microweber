<div class="posts-list ">
  <div class="single-post">
    <h1   class="post-title"> <a   class="post-title"  href="<? print post_link($post['id']); ?>"  itemprop="name"><? print $post['content_title'] ?></a></h1>
    <div class="single-post-info"> <span class="single-post-author">by <span itemprop="author"><? print user_name($post['created_by']); ?></span></span> | <span class="single-post-date">published on <span itemprop="datePublished"><? print ($post['created_on']); ?></span></span> </div>
    <editable rel="post" field="content_body"></editable>
  </div>
  <br />
    
  <br />
  <h2>Tell us what you think</h2>
  <br />
  <br />
  <div id="fb-root"></div>
  <fb:comments href="<? print url(); ?>" num_posts="20" width="650"></fb:comments>
</div>

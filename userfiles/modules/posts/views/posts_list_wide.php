<? $i = 0; foreach($posts as $the_post): ;?>

<div class="post_list"><a class="img" href="<? print post_link($the_post['id']); ?>" style="background-image: url('<? print thumbnail($the_post['id'], 150); ?>')"></a>
  <div class="post_list_content"> <span class="author">Posted by <? print user_name($the_post['created_by'], 'full'); ?></span>
    <h3><a href="<? print post_link($the_post['id']); ?>"><? print $the_post['content_title'];  ?></a></h3>
    <p><? print character_limiter($the_post['content_body_nohtml']);  ?> </p>
  </div>
  <div class="c">&nbsp;</div>
  <div class="user_activity_bar"> <a class="mw_blue_link left" href="<? print post_link($the_post['id']); ?>">Read more</a>
    <div> 
          <span class="st_sharethis" st_url="<? print post_link($the_post["id"]); ?>" st_title="<? print addslashes($the_post["content_title"]); ?>" displayText="Share this"></span>
    
     <a class="user_activity_comments" href="<? print post_link($the_post['id']); ?>"><strong><? print comments_count($the_post['id']); ?></strong><span></span><strong>Comments</strong></a> <a class="user_activity_likes" href="<? print voting_link($the_post['id'], '#post-likes-'.$the_post['id']); ?>"><strong id="post-likes-<? print ($the_post['id']); ?>"><? print votes_count($the_post['id']); ?></strong><span></span><strong >Like</strong></a> </div>
  </div>
</div>
<? if($i == 1 and isAjax()==false): ?>

<div class="post_list" align="center"><? include(TEMPLATE_DIR.'banner_wide.php')	; ?></div>
<? endif; ?>
<? $i++; endforeach; ?>

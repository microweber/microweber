<?  $the_post = get_post($the_post['id']); ?>
<div class="video_list_item">
  <a href="<? print post_link($the_post['id']); ?>" class="mw_blue_link">
      <? print $the_post['content_title'];  ?>
  </a>
  <a href="<? print post_link($the_post['id']); ?>" class="img" style="background-image:url(<? print thumbnail($the_post['id'], 150) ?>)"> </a>
  <a class="user_activity_likes left" href="<? print voting_link($the_post['id'], '#post-likes-'.$the_post['id']); ?>">
      <strong><? print votes_count($the_post['id']); ?></strong><span></span>
  </a>
  <? /*
  <a class="user_activity_comments right" href="<? print post_link($the_post['id']); ?>">
      <strong id="post-likes-<? print ($the_post['id']); ?>"><? print comments_count($the_post['id']); ?></strong><span></span>
  </a>
  */ ?>
  <span class="right red"><strong>$ <? print $the_post['custom_fields']['price'];  ?> </strong></span>
</div>

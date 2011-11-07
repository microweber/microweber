<div class="post_list">

<? $author = get_user($the_post['created_by']); ?>

<a href="<? print site_url('skide/userbase/action:profile/username:').user_name($the_post['created_by'], 'username'); ?>" class="user_photo left" style="margin-right: 7px;background-image: url('<? print user_thumbnail( $the_post['created_by'], 50) ?>')"></a>
<div class="post_list_content">

  <div style="float: left;width:305px;">
    <span class=""><? print user_name($the_post['created_by']); ?></span>
    <div class="question_text"> <a href="<? print post_link($the_post['id']); ?>"> <? print $the_post['content_title'];  ?></a></div>
  </div>
  <a href="<? print post_link($the_post['id']); ?>" class="mw_btn_x reply_btn right"><span><strong>Reply</strong></span></a>
        
  <a class="user_activity_comments right" style="margin: 8px 5px 0 0" href="<? print post_link($the_post['id']); ?>">
      <strong id="post-likes-<? print ($the_post['id']); ?>"><? print comments_count($the_post['id']); ?></strong><span></span>
  </a>

</div>
<div class="c">&nbsp;</div>
</div>
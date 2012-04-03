<? $author = get_user($the_post['created_by']); ?>

<div class="post_list">

<a href="<? print site_url('skide/userbase/action:profile/username:').user_name($the_post['created_by'], 'username'); ?>" class="user_photo left" style="margin-right: 7px;background-image: url('<? print user_thumbnail( $the_post['created_by'], 50) ?>')"></a>
<div class="post_list_content"> <span class=""><? print user_name($the_post['created_by']); ?></span><br />
  <span class="question_text"><? print $the_post['content_title'];  ?></span> <a href="javascript:add_edit_question('<? print $the_post['id'] ?>');" class="mw_btn_x reply_btn"><span><strong>Edit</strong></span></a> <a class="user_activity_comments right" href="<? print post_link($the_post['id']); ?>"><strong id="post-likes-<? print ($the_post['id']); ?>"><? print comments_count($the_post['id']); ?></strong><span></span></a> </div>
<div class="c">&nbsp;</div>


</div>
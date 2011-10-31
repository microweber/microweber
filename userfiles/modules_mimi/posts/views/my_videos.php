<? $author = get_user($the_post['created_by']); ?>

<div class="video_list_item"> <a class="mw_blue_link" href="<? print post_link($the_post['id']); ?>"><? print $the_post['content_title'];  ?></a> <a style="background-image: url('<? print thumbnail($the_post['id'], 120  ); ?>');" class="img" href="<? print post_link($the_post['id']); ?>"> </a> <a href="<? print post_link($the_post['id']); ?>" class="user_activity_comments right"><strong id=""><? print comments_count($the_post['id']); ?></strong><span></span></a> 

<? if($the_post['created_by'] == user_id()): ?>

<a href="javascript:add_edit_vid('<? print $the_post['id'] ?>');" class="left"><span><u>Edit</u></span></a>
<? endif; ?>


</div>

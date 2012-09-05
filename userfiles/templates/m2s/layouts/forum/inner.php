
<div class="speak_box">
  <? if($anon_users == false): ?>
  <div class="commentee_thumb" style="background-image:url('<? print user_thumbnail($item['created_by'], 90); ?>')"></div>
  <? endif; ?>
  <div class="commentee_text">
    <? if($anon_users == false): ?>
    <div class="commentee_name"><? print user_name($post['created_by']) ?></div>
    <? endif; ?>
    <div class="blog_title2"><? print $post['content_title'] ?></div>
    <? print $post['content_body'] ?> </div>
  <a  class="user_activity_likes right"  href="<? print voting_link($post['id'], '#post-likes-'.$post['id'], 'table_content'); ?>"><strong id="post-likes-<? print ($post['id']); ?>"><? print votes_count($post['id'], false,'table_content' ); ?></strong> Love</a> </div>
<div class="blog_entry_top">
  <div class="blog_title_inner">Would you like to reply?</div>
  <div class="blog_comments">
    <div class="comments_number"><? print  comments_count($content_id = $post['id'], $is_moderated = false, $for = 'post'); ?></div>
    <div class="comments_lable">Answers</div>
  </div>
</div>
<? require TEMPLATE_DIR. "blocks/comments.php"; ?>

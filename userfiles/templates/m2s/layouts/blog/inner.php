

<div class="agony_blk">
  <div class="blog_entry_top">
    <div class="blog_title"><? print $post['content_title'] ?></div>
    <div class="blog_comments">
      <div class="comments_number"><? print  comments_count($content_id = $post['id'], $is_moderated = false, $for = 'post'); ?></div>
      <div class="comments_lable">Comments</div>
    </div>
  </div>
  <div class="posted">Posted by <? print user_name($post['created_by']) ?> on <? print $post['created_on'] ?></div>
  <div class="blog_text <? print strtolower(url_title($page['content_title'])); ?>"> <? print $post['content_body'] ?> </div>
     <a  class="user_activity_likes right"  href="<? print voting_link($post['id'], '#post-likes-'.$post['id'], 'table_content'); ?>"><strong id="post-likes-<? print ($post['id']); ?>"><? print votes_count($post['id'], false,'table_content' ); ?></strong> Love</a>
  <div class="blog_entry_top">
    <div class="blog_title_inner">Would you like to comment?</div>
    <div class="blog_comments">
      <div class="comments_number"><? print  comments_count($content_id = $post['id'], $is_moderated = false, $for = 'post'); ?></div>
      <div class="comments_lable">Comments</div>
    </div>
  </div>
</div>

<? require TEMPLATE_DIR. "blocks/comments.php"; ?>

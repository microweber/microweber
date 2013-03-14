<?
 $data = array(
        'content_id' => $params['content_id']
    );

    $comments = get_comments($data);

	 $item = get_content_by_id($params['content_id']);



 $content_id =  $params['content_id'] ?>

<div class="comment-post">
  <div class="comment-info-holder" onclick="mw.adminComments.toggleMaster(this, event)"> <span class="img"> <img src="<?php print thumbnail(get_picture($content_id),67,67); ?>" alt="" />
    <?php $new = get_comments('count=1&is_moderated=n&to_table=table_content&to_table_id='.$content_id); ?>
    <?php if($new > 0){ ?>
    <span class="comments_number"><?php print $new; ?></span>
    <?php } ?>
    </span>
    <div class="comment-post-content-side">
      <h3><a href="javascript:;" class="mw-ui-link"><? print $item['title'] ?></a></h3>
      <a class="comment-post-url" href="<? print content_link($item['id']) ?>"> <? print content_link($item['id']) ?></a> <br>
      <a class="mw-ui-link" href="<? print $item['url'] ?>/editmode:y">Live edit</a> </div>
  </div>
  <div class="comments-holder">
    <div class="new-comments">
      <?php $count_new = 0;  foreach ($comments as $comment){ ?>

      <?php if ($comment['is_moderated'] == 'n'){ $count_new ++; ?>
      <div class="comment-of-apost new-comment" id="comment-<?php print $comment['id']; ?>"> <a href="<?php print $comment['comment_website']; ?>" class="mw-ui-link"><?php print $comment['comment_name']; ?></a>
        <p class="comment-body"><?php print $comment['comment_body']; ?></p>
        <input type="hidden" name="id" value="<? print $comment['id'] ?>">
        <input type="hidden" name="connected_id" value="<? print $comment['to_table_id'] ?>">
        <input type="text" name="action" class="comment_state semi_hidden" />
        <div class="manage-bar">
          <div class="edit-comment">
            <textarea name="comment_body"><?php print $comment['comment_body']; ?></textarea>
            <a href="javascript:;" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'update');" class="mw-ui-btn mw-ui-btn-small mw-ui-btn right">Update</a>
            <div class="mw_clear"></div>
          </div>
          <span class="mw-ui-btn mw-ui-btn-small mw-ui-btn" onclick="mw.adminComments.toggleEdit('#comment-<?php print $comment['id'];?>');">Edit</span> <span class="mw-ui-btn mw-ui-btn-small mw-ui-btn-blue" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'publish')">Publish</span> <span class="mw-ui-btn mw-ui-btn-small mw-ui-btn-red" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'delete')">Delete</span> </div>
      </div>
      <?php } ?>
      <?php } ?>
    </div>
    <div class="old-comments">
      <?php  $count_old = 0; foreach ($comments as $comment){ ?>
      <?php if ($comment['is_moderated'] == 'y'){ $count_old ++; ?>
      <div class="comment-of-apost" id="comment-<?php print $comment['id']; ?>"> <a href="<?php print $comment['comment_website']; ?>" class="mw-ui-link"><?php print $comment['comment_name']; ?></a>
        <p class="comment-body"><?php print $comment['comment_body']; ?></p>
        <input type="hidden" name="id" value="<? print $comment['id'] ?>">
        <input type="text" name="action" class="comment_state semi_hidden" />
        <input type="hidden" name="connected_id" value="<? print $comment['to_table_id'] ?>">
        <div class="manage-bar">
          <div class="edit-comment">
            <textarea name="comment_body"><?php print $comment['comment_body']; ?></textarea>
            <a href="javascript:;" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'update');" class="mw-ui-btn mw-ui-btn-small mw-ui-btn right">Update</a>
            <div class="mw_clear"></div>
          </div>
          <span class="mw-ui-btn mw-ui-btn-small mw-ui-btn" onclick="mw.adminComments.toggleEdit('#comment-<?php print $comment['id'];?>');">Edit</span> <span class="mw-ui-btn mw-ui-btn-small" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'unpublish')">Unpublish</span> <span class="mw-ui-btn mw-ui-btn-small mw-ui-btn-red" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'delete')">Delete</span> </div>
      </div>
      <?php } ?>
      <?php } ?>
    </div>
  </div>
  <div class="comments-show-btns"> <span class="mw-ui-btn comments-show-all" onclick="mw.adminComments.display(event,this, 'all');"><?php print ($count_old+$count_new); ?> All</span> <span class="mw-ui-btn mw-ui-btn-green comments-show-new" onclick="mw.adminComments.display(event,this, 'new');"><?php print $count_new; ?> New</span> </div>
</div>

<? if(!empty($comments)): ?>
    <div class="new-comments">
      <?php $count_new = 0;  foreach ($comments as $comment){ ?>
          <?php if ($comment['is_new'] == 'y'){ $count_new ++; ?>
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
              <span class="mw-ui-btn mw-ui-btn-small mw-ui-btn" onclick="mw.adminComments.toggleEdit('#comment-<?php print $comment['id'];?>');">Edit</span>
               <?php if($moderation_is_required){ ?>
               <?php if($comment['is_moderated'] == 'y') { ?>
                    <span class="mw-ui-btn mw-ui-btn-small" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'unpublish')">Unpublish</span>
               <?php } else { ?>
                    <span class="mw-ui-btn mw-ui-btn-small mw-ui-btn-blue" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'publish')">Publish</span>
               <?php }} ?>
                     

              <span class="mw-ui-btn mw-ui-btn-small mw-ui-btn-red" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'delete')">Delete</span> </div>
          </div>
          <?php } ?>
      <?php } ?>
    </div>
    <div class="old-comments">
      <?php  $count_old = 0; foreach ($comments as $comment){ ?>
        <?php if ($comment['is_new'] == 'n'){ $count_old ++; ?>
        <div class="comment-of-apost<?php if($comment['is_moderated'] == 'n' and $moderation_is_required) { ?> comment-unpublished<?php } ?>" id="comment-<?php print $comment['id']; ?>"> <a href="<?php print $comment['comment_website']; ?>" class="mw-ui-link"><?php print $comment['comment_name']; ?></a>
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
            <span class="mw-ui-btn mw-ui-btn-small mw-ui-btn" onclick="mw.adminComments.toggleEdit('#comment-<?php print $comment['id'];?>');">Edit</span>
            <?php if($moderation_is_required){ ?>
            <?php if($comment['is_moderated'] == 'y') { ?>
                  <span class="mw-ui-btn mw-ui-btn-small" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'unpublish')">Unpublish</span>
             <?php } else { ?>
                  <span class="mw-ui-btn mw-ui-btn-small mw-ui-btn-blue" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'publish')">Publish</span>
             <?php }} ?>
            <span class="mw-ui-btn mw-ui-btn-small mw-ui-btn-red" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'delete')">Delete</span>
          </div>
        </div>
        <?php } ?>
      <?php } ?>
    <? endif; ?>
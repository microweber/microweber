<?php if(!empty($comments)): ?>

<div class="new-comments">
	<?php $count_new = 0;  foreach ($comments as $comment){ ?>
	<?php if ($comment['is_new'] == 1){ $count_new ++; ?>
	<form class="comment-of-apost new-comment" id="comment-<?php print $comment['id']; ?>"> <a href="<?php print $comment['comment_website']; ?>" class="mw-ui-link"><?php print $comment['comment_name']; ?></a> <small class="edit-comment-date"><?php print $comment['created_at']; ?> (<?php print mw('format')->ago($comment['created_at']) ?>)</small>
		<p class="comment-body"><?php print strip_tags($comment['comment_body']); ?></p>
		<input type="hidden" name="id" value="<?php print $comment['id'] ?>">
		<input type="hidden" name="connected_id" value="<?php print $comment['rel_id'] ?>">
		<input type="text" name="action" class="comment_state semi_hidden" />
		<div class="manage-bar">
			<div class="edit-comment">
                <small  class="edit-comment-date"><?php print $comment['created_at']; ?> (<?php print mw('format')->ago($comment['created_at']) ?>)</small>
				<textarea class="mw-ui-field" name="comment_body" onkeyup="mw.tools.addClass(this, 'comment-changed');" onpaste="mw.tools.addClass(this, 'comment-changed');"><?php print strip_tags($comment['comment_body']); ?></textarea>
				<a href="javascript:;" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'update');" class="mw-ui-btn mw-ui-btn-small pull-right update-comment-button">
				<?php _e("Update"); ?>
				</a>

			</div>
			<span class="mw-ui-btn mw-ui-btn-small mw-ui-btn" onclick="mw.adminComments.toggleEdit('#comment-<?php print $comment['id'];?>');">
			<?php _e("Edit"); ?>
			</span>
			<?php if($moderation_is_required){ ?>
			<?php if($comment['is_moderated'] == 1) { ?>
			<span class="mw-ui-btn mw-ui-btn-small" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'unpublish')">
			<?php _e("Unpublish"); ?>
			</span>
			<?php } else { ?>
			<span class="mw-ui-btn mw-ui-btn-small mw-ui-btn-blue" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'publish')">
			<?php _e("Publish"); ?>
			</span>
			<?php }} ?>
			<span class="mw-ui-btn mw-ui-btn-small mw-ui-btn-red" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'delete')">
			<?php _e("Delete"); ?>
			</span> </div>
	</form>
	<?php } ?>
	<?php } ?>
</div>
<div class="old-comments">
<?php  $count_old = 0; foreach ($comments as $comment){ ?>
<?php if ($comment['is_new'] == 0){ $count_old ++; ?>
<form class="comment-of-apost<?php if($comment['is_moderated'] == 0 and $moderation_is_required) { ?> comment-unpublished<?php } ?>" id="comment-<?php print $comment['id']; ?>"> <a href="<?php print $comment['comment_website']; ?>" class="mw-ui-link"><?php print $comment['comment_name']; ?></a> <small class="edit-comment-date"><?php print $comment['created_at']; ?> (<?php print mw('format')->ago($comment['created_at']) ?>)</small>
	<p class="comment-body"><?php print strip_tags($comment['comment_body']); ?></p>
	<input type="hidden" name="id" value="<?php print $comment['id'] ?>">
	<input type="text" name="action" class="comment_state semi_hidden" />
	<input type="hidden" name="connected_id" value="<?php print $comment['rel_id'] ?>">
	<div class="manage-bar">
		<div class="edit-comment">
			<textarea name="comment_body" class="mw-ui-field" onkeyup="mw.tools.addClass(this, 'comment-changed');" onpaste="mw.tools.addClass(this, 'comment-changed');"><?php print $comment['comment_body']; ?></textarea>
			<a href="javascript:;" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'update');" class="mw-ui-btn mw-ui-btn-green mw-ui-btn-small mw-ui-btn right update-comment-button">
			<?php _e("Update"); ?>
			</a>

		</div>
		<span class="mw-ui-btn mw-ui-btn-small mw-ui-btn" onclick="mw.adminComments.toggleEdit('#comment-<?php print $comment['id'];?>');">Edit</span>
		<?php if($moderation_is_required){ ?>
		<?php if($comment['is_moderated'] == 1) { ?>
		<span class="mw-ui-btn mw-ui-btn-small" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'unpublish')">
		<?php _e("Unpublish"); ?>
		</span>
		<?php } else { ?>
		<span class="mw-ui-btn mw-ui-btn-small mw-ui-btn-blue" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'publish')">
		<?php _e("Publish"); ?>
		</span>
		<?php }} ?>
		<span class="mw-ui-btn mw-ui-btn-small mw-ui-btn-red" onclick="mw.adminComments.action(mwd.getElementById('comment-<?php print $comment['id'];?>'), 'delete')">
		<?php _e("Delete"); ?>
		</span> </div>
</form>
<?php } ?>
<?php } ?>
<?php endif; ?>
</div>

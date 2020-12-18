<?php 
$comment_id = (int) $params['id'];
$comment = get_comments('single=1&id=' . $comment_id);
if (empty($comment)) {
	return;
}
?>
<script type="text/javascript">
$(document).ready(function () {
	
	mw.editor({ element: '#mw-comment-edit-textarea' });

	$('.js-save-comment-btn').click(function() {
 		
 		$.ajax({
 			  type: "POST",
 			  url: mw.settings.api_url + 'save_comment_user',
 			  data: "comment_id=<?php echo $comment_id; ?>&comment_body="+ $('#mw-comment-edit-textarea').val(),
 			  success: function() {
 				mw.reload_module('comments');
 				mw.notification.success('Comment saved!');
 			  }
 		});
 		
	});
	
});
</script>
<h5>Edit comment</h5>
<div class="comment_body">
<textarea id="mw-comment-edit-textarea" class="mw-ui-field" name="comment_body"><?php echo $comment['comment_body']; ?></textarea>
<button class="js-save-comment-btn mw-ui-btn mw-ui-btn-notification mw-ui-btn-outline mw-ui-btn-small">
<i class="mw-icon-pen"></i> Save comment
</button>
</div>
<script>
function edit_comment_user(id = false) {
	var data = {};
    data.id = id;
	edit_list_modal = mw.tools.open_module_modal('comments/edit_comment_user', data, {overlay: true, skin: 'simple'});
}
function delete_comment_user(id) { 

	
}
</script>
<div class="comment-actions" style="float: right;">
	<a href="javascript:edit_comment_user(<?php echo $params['comment_id']; ?>);" class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-small mw-ui-btn-info">Edit</a>
	<a href="javascript:delete_comment_user(<?php echo $params['comment_id']; ?>);" class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-small mw-ui-btn-important">Delete</a>
</div>
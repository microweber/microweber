<script type="text/javascript">
function upload_template_modal() {
	mw.modal({
	    content: '<div id="mw_admin_upload_template_modal_content"></div>',
	    title: 'Upload Template',
	    id: 'mw_admin_upload_template_modal'
	});
	var params = {};
	mw.load_module('admin/templates/upload', '#mw_admin_upload_template_modal_content', null, params);
}
</script>

<button onClick="upload_template_modal()" class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline tip" title="<?php _e('Upload new template'); ?>" type="button" style="margin-top: 53px; text-align: center;">
	<i class="mw-icon-upload m-0"></i> <?php _e('Upload new template'); ?>
</button>
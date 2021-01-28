<script type="text/javascript">
    function upload_template_modal() {
        mw.dialog({
            content: '<div id="mw_admin_upload_template_modal_content"></div>',
            title: 'Upload Template',
            id: 'mw_admin_upload_template_modal'
        });
        var params = {};
        mw.load_module('admin/templates/upload', '#mw_admin_upload_template_modal_content', null, params);
    }
</script>

<button onClick="upload_template_modal()" class="btn btn-outline-primary mb-3" type="button"><?php _e('Upload new template'); ?></button>

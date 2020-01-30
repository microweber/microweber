<?php only_admin_access(); ?>
<link rel="stylesheet" href="<?php echo modules_url() . '/admin/backup_v2/css/style.css' ?>" type="text/css" />
<script  type="text/javascript">
	var importContentFromFileText = '<?php _e("Importing content from file"); ?>';
	var userfilesUrl = '<?php echo userfiles_url() ?>';
	var moduleImagesUrl = '<?php echo modules_url() . '/admin/backup_v2/images/' ?>';
	mw.require("<?php print $config['url_to_module']; ?>js/upload-file.js");
    mw.require("<?php print $config['url_to_module']; ?>js/backup-import.js?v=10");
    mw.require("<?php print $config['url_to_module']; ?>js/backup-export.js?v=10");
</script>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div id="mw-admin-content" class="admin-side-content">

    <div class="mw_edit_page_default" id="mw_edit_page_left">

        <div class="mw-ui-btn-nav pull-left">
            <a href="javascript:;" onclick="mw.backup_export.choice()" class="mw-ui-btn mw-ui-btn-notification">
                <i class="mw-icon-download"></i>&nbsp; <span><?php _e("Create backup & export content"); ?></span>
            </a>
        </div>
        
        <span id="mw_uploader" class="mw-ui-btn mw-ui-btn-info pull-right">
            <i class="mw-icon-upload"></i>&nbsp;
            <span><?php _e("Upload file"); ?></span>
        </span>

		<!-- Upload file notification loader -->
        <div id="mw_uploader_loading" class="mw-ui-btn mw-ui-btn-notification" style="display:none;"><?php _e("Uploading files"); ?></div>

        <div class="vSpace">&nbsp;</div>
    </div>

    <div class="mw_edit_page_right" style="padding: 10px 0;">
        <module type="admin/backup_v2/manage"/>
    </div>
    
</div>
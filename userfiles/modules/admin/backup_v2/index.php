<?php only_admin_access(); ?>
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

<style>
    .mw-backup-v2-import {
        padding-right:40px;
        padding-left:40px;
        text-align: center;
    }
    .mw-backup-v2-import > .import-image {
        display: none;
    }
    .mw-backup-v2-import-option {
        padding-left:15px;
        padding-bottom: 15px;
        background:#FFF;
        border: 1px solid #ebebeb;
        margin-bottom: 15px;
        width:100%;
        text-align: left;
    }    .mw-backup-v2-import-option.active {
        box-shadow: 0px 4px 11px #e6e6e6;
    }
    .mw-backup-v2-import-option:hover {
        box-shadow: 0px 4px 11px #e6e6e6;
    }
    .mw-backup-v2-import-option h3 {
        font-weight: bold;
        font-size: 18px;
    }
    .mw-backup-v2-import-option p {
        font-size: 13px;
    }
    .mw-backup-v2-import-option > .option-radio {
        width: 50px;
        height: 85px;
        float: left;
        padding-top: 25px;
        padding-left: 10px;
    }
    .mw-backup-v2-import-buttons {
        text-align: right;
    }
    .mw-backup-v2-import-buttons > .button-cancel {
         margin-right: 25px;
        font-weight: bold;
        cursor: pointer;
        padding: 15px;
     }
</style>

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
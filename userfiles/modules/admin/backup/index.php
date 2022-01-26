<?php must_have_access(); ?>
<link rel="stylesheet" href="<?php echo modules_url() . '/admin/backup/css/style.css' ?>" type="text/css"/>

<script type="text/javascript">
    var importContentFromFileText = '<?php _e("Restore from file"); ?>';
    var userfilesUrl = '<?php echo userfiles_url() ?>';
    var moduleImagesUrl = '<?php echo modules_url() . '/admin/backup/images/' ?>';
    mw.require("<?php print $config['url_to_module']; ?>js/upload-file.js");
    mw.require("<?php print $config['url_to_module']; ?>js/backup.js?v=10");
    mw.require("<?php print $config['url_to_module']; ?>js/restore.js?v=10");
    mw.lib.require('mw_icons_mind');

</script>
<?php $here = $config['url_to_module']; ?>

<div class="row mt-3">
    <div class="col-6 mb-4">
        <?php if (user_can_access('module.admin.backup.index')): ?>
            <h5 class="font-weight-bold"><?php _e('Create new backup'); ?></h5>
            <small class="text-muted d-block mb-3"><?php _e('Create a backup and export your website content'); ?></small>
            <a href="javascript:;" onclick="mw.backup_export.choice('#export-template')" class="btn btn-success btn-rounded"><i class="mdi mdi-plus"></i> <?php _e("Create backup"); ?></a>
        <?php endif; ?>
    </div>

    <?php if (user_can_access('module.admin.backup.create') || user_can_access('module.admin.backup.edit')): ?>
        <div class="col-6 mb-4">
            <h5 class="font-weight-bold"><?php _e('Upload your backup'); ?></h5>
            <small class="text-muted d-block mb-3"><?php _e("Upload your backup file (must be zip)"); ?></small>
            <span id="mw_uploader" class="btn btn-primary btn-rounded"><i class="mdi mdi-cloud-upload-outline"></i>&nbsp; <?php _e("Upload file"); ?></span>

            <div id="mw_uploader_loading" class="progress mb-3" style="display:none;">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="mw_edit_page_right">
    <module type="admin/backup/manage"/>
</div>

<?php only_admin_access(); ?>

<?php $got_lic = mw()->update->get_licenses('count=1') ?>

<script>
    function openModuleInModal(module, title) {
        if(!title){
            title = '';
        }
        var dialog = mw.dialog({'title': title});
        mw.load_module(module, dialog.dialogContainer);
    }
</script>

<div class="d-none">
    <div class="js-modal-content">
        <div></div>
    </div>
</div>

<div class="form-group">
    <label class="control-label"><?php _e("Developers tools"); ?></label>
    <small class="text-muted d-block mb-2">If you are a developer, then these tools would be useful to you.</small>
</div>

<div class="form-group">
    <label class="control-label"><?php _e("Template Backup"); ?></label>
    <small class="text-muted d-block mb-2">Make a backup of your template website.</small>
    <a href="javascript:;" class="btn btn-outline-primary btn-sm" onclick="openModuleInModal('admin/developer_tools/template_exporter', 'Template backup')"><?php _e('Export template'); ?></a>
</div>

<div class="form-group">
    <label class="control-label"><?php _e('Media cleanup'); ?></label>
    <small class="text-muted d-block mb-2">This module will remove media from database, which is not present on the hard drive.</small>
    <a href="javascript:;" class="btn btn-outline-primary btn-sm" onclick="openModuleInModal('admin/developer_tools/media_cleanup', 'Media cleanup')">Cleanup the images</a>
</div>

<div class="form-group">
    <label class="control-label"><?php _e("Database cleanup"); ?></label>
    <small class="text-muted d-block mb-2">This module will remove categories, images and custom fields which are connected to a content that is manually deleted from the database.</small>
    <a href="javascript:;" class="btn btn-outline-primary btn-sm" onclick="openModuleInModal('admin/developer_tools/database_cleanup', 'Database cleanup')"><?php _e('Database cleanup'); ?></a>
</div>

<div class="form-group">
    <label class="control-label"><?php _e("Show system log"); ?></label>
    <small class="text-muted d-block mb-2">Show sistem logs for the last 30 days.</small>
    <a href="javascript:;" class="btn btn-outline-primary btn-sm" onclick="openModuleInModal('admin/notifications/system_log', 'Show system log')"><?php _e("Show system log"); ?></a>
</div>

<?php if (($got_lic) >= 0): ?>
    <div class="form-group">
        <label class="control-label"><?php _e("Licenses"); ?></label>
        <small class="text-muted d-block mb-2">Add or edit your licenses.</small>
        <a href="javascript:;" class="btn btn-outline-primary btn-sm" onclick="openModuleInModal('settings/group/licenses', 'Licenses')"><?php _e("Licenses"); ?></a>
    </div>
<?php endif; ?>

<div class="form-group">
    <label class="control-label"><?php _e("Cache settings"); ?></label>
    <small class="text-muted d-block mb-2">Speed up your website load speed.</small>
</div>

<?php if (config('app.debug')): ?>
    <a href="javascript:;" class="btn btn-outline-primary btn-sm" onclick="openModuleInModal('settings/group/experimental', 'Cache settings')"><?php _e("Experimental settings"); ?></a>
    <?php /* <br /><br />   <a href="javascript:;" class="btn btn-outline-primary btn-sm" onclick="mw.load_module('admin/modules/packages')"> <?php  _e("Packages");  ?></a>  */ ?>
<?php endif; ?>


<div id="mw-advanced-settings-module-load-holder"></div>
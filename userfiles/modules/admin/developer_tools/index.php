<?php must_have_access(); ?>

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
    <small class="text-muted d-block mb-2"><?php _e('If you are a developer, then these tools would be useful to you'); ?>.</small>
</div>

<div class="form-group">
    <label class="control-label"><?php _e("Template Backup"); ?></label>
    <small class="text-muted d-block mb-2"><?php _e('Make a backup of your template website'); ?>.</small>
    <a href="javascript:;" class="btn btn-outline-primary btn-sm" onclick="openModuleInModal('admin/developer_tools/template_exporter', 'Template backup')"><?php _e('Export template'); ?></a>
</div>

<div class="form-group">
    <label class="control-label"><?php _e('Media cleanup'); ?></label>
    <small class="text-muted d-block mb-2"><?php _e('This module will remove media from database, which is not present on the hard drive'); ?>.</small>
    <a href="javascript:;" class="btn btn-outline-primary btn-sm" onclick="openModuleInModal('admin/developer_tools/media_cleanup', 'Media cleanup')"><?php _e("Cleanup the images"); ?></a>
</div>

<div class="form-group">
    <label class="control-label"><?php _e("Database cleanup"); ?></label>
    <small class="text-muted d-block mb-2"><?php _e('This module will remove categories, images and custom fields which are connected to a content that is manually deleted from the database'); ?>.</small>
    <a href="javascript:;" class="btn btn-outline-primary btn-sm" onclick="openModuleInModal('admin/developer_tools/database_cleanup', 'Database cleanup')"><?php _e('Database cleanup'); ?></a>
</div>

<div class="form-group">
    <label class="control-label"><?php _e("Show system log"); ?></label>
    <small class="text-muted d-block mb-2"><?php _e('Show system logs for the last 30 days'); ?>.</small>
    <a href="javascript:;" class="btn btn-outline-primary btn-sm" onclick="openModuleInModal('admin/notifications/system_log', 'Show system log')"><?php _e("Show system log"); ?></a>
</div>

<?php
$showLicensesLink = mw()->ui->enable_service_links();
$showPoweredBy = mw()->ui->powered_by_link_enabled();

if ($showLicensesLink and $showPoweredBy and ($got_lic) >= 0): ?>
    <div class="form-group">
        <label class="control-label"><?php _e("Licenses"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e('Add or edit your licenses'); ?>.</small>
        <a href="javascript:;" class="btn btn-outline-primary btn-sm" onclick="openModuleInModal('settings/group/licenses', 'Licenses')"><?php _e("Licenses"); ?></a>
    </div>
<?php endif; ?>

<div class="form-group">
    <label class="control-label"><?php _e("Cache settings"); ?></label>
    <small class="text-muted d-block mb-2"><?php _e('Speed up your website load speed'); ?>.</small>
    <a href="<?php echo admin_url('view:modules/load_module:fullpage_cache');?>" class="btn btn-outline-success"><?php _e("Run fullpage cache"); ?></a>
</div>

<script>
    function clearMwCache() {
        mw.clear_cache();
        mw.notification.success("<?php _ejs("The cache was cleared"); ?>.");
    }
</script>

<a href="javascript:;" class="btn btn-outline-primary btn-sm" onclick="openModuleInModal('settings/group/cache', 'Cache settings')"><?php _e("Cache settings"); ?></a>
<a class="btn btn-outline-danger btn-sm" href="javascript:clearMwCache();"><?php _e("Clear cache"); ?></a>

<?php if (config('app.debug')): ?>
    <?php /* <br /><br />   <a href="javascript:;" class="btn btn-outline-primary btn-sm" onclick="mw.load_module('admin/modules/packages')"> <?php  _e("Packages");  ?></a>  */ ?>
<?php endif; ?>

<?php must_have_access(); ?>

<?php $got_lic = mw()->update->get_licenses('count=1') ?>

<script>
    function openModuleInModal(module, title) {
        if(!title){
            title = '';
        }
        var dialog = mw.dialog({'title': title, 'width': 900});
        mw.load_module(module, dialog.dialogContainer);
    }
</script>

<div class="d-none">
    <div class="js-modal-content">
        <div></div>
    </div>
</div>


<div class="d-flex flex-wrap mx-auto justify-content-center mb-3">
    <div class="col-xl-6 col-12 my-xl-0 my-3">
        <div class="card mx-2 h-100">
            <div class="card-body">
                <label class="form-label"><?php _e("Template Backup"); ?></label>
                <small class="advanced-settings-small-helper  d-block mb-2"><?php _e('This module will make a backup of your template website with your chosen method.'); ?>.</small>
                <a href="javascript:;" class="btn btn-outline-primary btn-sm" onclick="openModuleInModal('admin/developer_tools/template_exporter', 'Template backup')"><?php _e('Export template'); ?></a>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-12 my-xl-0 my-3">
        <div class="card mx-2 h-100">
            <div class="card-body">
                <label class="form-label"><?php _e('Media cleanup'); ?></label>
                <small class="advanced-settings-small-helper  d-block mb-2"><?php _e('This module will remove media from database, which is not present on the hard drive'); ?>.</small>
                <a href="javascript:;" class="btn btn-outline-primary btn-sm" onclick="openModuleInModal('admin/developer_tools/media_cleanup', 'Media cleanup')"><?php _e("Cleanup the images"); ?></a>
            </div>
        </div>
    </div>
</div>


<div class="d-flex flex-wrap mx-auto justify-content-center mt-3">
    <div class="col-xl-6 col-12 my-xl-0 my-3">
        <div class="card mx-2 h-100">
            <div class="card-body">
                <label class="form-label"><?php _e("Database cleanup"); ?></label>
                <small class="advanced-settings-small-helper d-block mb-2"><?php _e('Remove categories, images and custom fields, connected to a content, manually deleted from the database'); ?>.</small>
                <a href="javascript:;" class="btn btn-outline-primary btn-sm" onclick="openModuleInModal('admin/developer_tools/database_cleanup', 'Database cleanup')"><?php _e('Database cleanup'); ?></a>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-12 my-xl-0 my-3">
        <div class="card mx-2 h-100">
            <div class="card-body">
                <label class="form-label"><?php _e("Show system log"); ?></label>
                <small class="advanced-settings-small-helper d-block mb-2"><?php _e('Show system logs for the last 30 days.'); ?>.</small>
                <a href="javascript:;" class="btn btn-outline-primary btn-sm" onclick="openModuleInModal('admin/notifications/system_log', 'Show system log')"><?php _e("Show system log"); ?></a>
            </div>
        </div>
    </div>
</div>

<div class="d-flex flex-wrap mx-auto justify-content-center mt-3">
    <div class="col-xl-6 col-12 my-xl-0 my-3">
        <div class="card mx-2 h-100">
            <div class="card-body">
                <?php
                $showLicensesLink = mw()->ui->enable_service_links();
                $showPoweredBy = mw()->ui->powered_by_link_enabled();

                if ($showLicensesLink and $showPoweredBy and ($got_lic) >= 0): ?>
                        <label class="form-label"><?php _e("Licenses"); ?></label>
                        <small class="advanced-settings-small-helper  d-block mb-2"><?php _e('Add or edit your licenses'); ?>.</small>
                        <a href="javascript:;" class="btn btn-outline-primary btn-sm" onclick="openModuleInModal('settings/group/licenses', 'Licenses')"><?php _e("Licenses"); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-12 my-xl-0 my-3">
        <div class="card mx-2 h-100">
            <div class="card-body">
                <label class="form-label"><?php _e("Cache settings"); ?></label>
                <small class="advanced-settings-small-helper  d-block mb-2"><?php _e('Speed up your website load speed'); ?>.</small>
                <a href="javascript:;" class="btn btn-outline-primary btn-sm" onclick="openModuleInModal('settings/group/cache', 'Cache settings')"><?php _e("Cache settings"); ?></a>
                <a class="btn btn-outline-primary btn-sm" href="javascript:clearMwCache();"><?php _e("Clear cache"); ?></a>
            </div>
        </div>
    </div>
</div>


<script>
    function clearMwCache() {
        mw.clear_cache();
        mw.notification.success("<?php _ejs("The cache was cleared"); ?>.");
    }
</script>



<?php if (config('app.debug')): ?>
    <?php /* <br /><br />   <a href="javascript:;" class="btn btn-outline-primary btn-sm" onclick="mw.load_module('admin/modules/packages')"> <?php  _e("Packages");  ?></a>  */ ?>
<?php endif; ?>

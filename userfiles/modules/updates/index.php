<?php only_admin_access(); ?>
<?php
if (mw()->ui->disable_marketplace) {
    return;
}
?>

<?php if (MW_VERSION < '1.2.0'): ?>
    <script>
        mw.lib.require('bootstrap4');
    </script>
<?php endif; ?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3">
    <div class="card-header">
        <?php $module_info = module_info($params['module']); ?>
        <h5>
            <?php if (isset($module_info['icon'])):?>
                <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/>
                <strong><?php _e('System updates'); ?></strong>
            <?php endif; ?>
        </h5>
    </div>

    <div class="card-body pt-3">
        <?php
        if (is_module('standalone-updater')) {
        ?>
            <a href="<?php echo admin_url('view:modules/load_module:standalone-updater');?>" class="btn btn-primary"><i class="fa fa-clock"></i> <?php _e('Check for system updates'); ?></a>
        <?php
        } else {
        ?>
            <button class="btn btn-primary" onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('microweber-modules/standalone-updater');"><i class="fa fa-download"></i> <?php _e('Install Standalone Updater'); ?></button>

            <script>
                $(document).ready(function () {
                    mw.on('install_composer_package_success', function (e, data) {
                        window.location.href = '<?php echo admin_url('view:modules/load_module:standalone-updater');?>';
                    });
                });
            </script>
        <?php
        }
        ?>

    </div>
</div>
<br />
<br />

<module type="admin/packages" show_only_updates="true" />


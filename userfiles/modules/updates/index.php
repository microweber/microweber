<?php only_admin_access(); ?>
<?php
if (mw()->ui->disable_marketplace) {
    return;
}
?>


<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>

<?php endif; ?>

<div class="d-flex justify-content-between">
        <strong class="main-pages-title"><?php _e('System updates'); ?></strong>
        <div>
            <?php
            if (is_module('standalone-updater')) {
                ?>
                <a href="<?php echo admin_url('module/view?type=standalone-updater');?>" class="btn btn-primary"><i class="fa fa-clock"></i> <?php _e('Check for system updates'); ?></a>
            <?php
            } else {
            ?>
                <button class="btn btn-primary" onclick="mw.admin.admin_package_manager.install_composer_package_by_package_name('microweber-modules/standalone-updater');"><i class="fa fa-download"></i> <?php _e('Install Standalone Updater'); ?></button>

                <script>
                    $(document).ready(function () {
                        mw.on('install_composer_package_success', function (e, data) {
                            window.location.href = '<?php echo admin_url('module/view?type=standalone-updater');?>';
                        });
                    });
                </script>
                <?php
            }
            ?>
        </div>
</div>

<module type="admin/packages" show_only_updates="true" />


<?php
$id = false;

if (isset($params["data-module-id"])) {
    $id = $params["data-module-id"];
}

$data = array();
if ($id != false) {
    $data = mw()->module_manager->get('ui=any&limit=1&id=' . $id);
    if (isset($data[0])) {
        $data = $data[0];
    }
}
?>
<?php if (!empty($data)): ?>
    <script>mw.lib.require('mwui_init');</script>
    <script type="text/javascript">
        $(document).ready(function () {
            mw.$('#module_admin_settings_form_<?php print $params['id']; ?>').submit(function () {
                mw.form.post(mw.$('#module_admin_settings_form_<?php print $params['id']; ?>'), '<?php print site_url('api') ?>/module/save', function () {
                });

                return false;
            });

            mw.$('#module_uninstall_<?php print $params['id']; ?>').unbind('click');
            mw.$('#module_uninstall_<?php print $params['id']; ?>').click(function (e) {
                e.stopPropagation();
                e.preventDefault();

                if (confirm("<?php _ejs('Are you sure you want to UNINSTALL this module?'); ?>")) {
                    var for_module = {}
                    for_module.id = $(this).attr('data-module-id');
                    $.post('<?php print site_url('api') ?>/uninstall_module/', for_module, function (data) {
                        $('#module_uninstall_<?php print $params['id']; ?>').hide();
                        $('#module_install_<?php print $params['id']; ?>').show();
                        $('#module_open_<?php print $params['id']; ?>').hide();
                        mw.notification.success("<?php _e('Module uninstalled'); ?>");
                    });
                    return false;
                }
            });

            mw.$('#module_install_<?php print $params['id']; ?>').unbind('click');
            mw.$('#module_install_<?php print $params['id']; ?>').click(function () {
                mw.notification.success("<?php _e('Installing... please wait'); ?>");

                var for_module = {}
                for_module.for_module = $(this).attr('data-module-name');
                $.post('<?php print site_url('api') ?>/install_module/', for_module, function (data) {
                    $('#module_install_<?php print $params['id']; ?>').hide();

                    $('#module_uninstall_<?php print $params['id']; ?>').show();
                    $('#module_open_<?php print $params['id']; ?>').show();
                    mw.notification.success("<?php _e('Module is installed'); ?>");

                });

                return false;
            });

            mw.$('#module_update_<?php print $params['id']; ?>').unbind('click');
            mw.$('#module_update_<?php print $params['id']; ?>').click(function () {
                //  var for_module = {}
                var for_module = $(this).attr('data-module-name');
                mw.notification.warning("Installing update for module: " + for_module + '');

                $.post('<?php print admin_url() ?>view:modules?add_module=' + for_module, function (data) {
                    mw.notification.success("<?php _e('New update for module <b>'); ?> " + for_module + '<?php _e('</b> is installed'); ?>');
                });

                return false;
            });
        });
    </script>

    <style>
        .module-img {
            height: 35px;
            margin-bottom: 10px;
        }

        .mw-modules-module-holder {
            min-height: 140px;
            cursor: pointer;
        }
    </style>

    <div class="card style-1 h-100 mw-modules-module-holder">
        <div class="card-body h-100 d-flex align-items-center justify-content-center flex-column" <?php if (strval($data['installed']) != '' and intval($data['installed']) != 0): ?>onclick="window.location.href = '<?php print admin_url() ?>view:modules/load_module:<?php print module_name_encode($data['module']) ?>';"<?php endif; ?>>
            <form class="admin-modules-list-form <?php if (strval($data['installed']) != '' and intval($data['installed']) != 0) {
                print 'module-installed';
            } else {
                print 'module-uninstalled';
            } ?> " id="module_admin_settings_form_<?php print $params['id']; ?>">
                <div class="d-flex align-items-center justify-content-center flex-column">
                    <?php if (isset($data['icon'])): ?>
                        <img src="<?php print $data['icon'] ?>" class="svg module-img" x-data-toggle="tooltip" data-title="<?php print $data['module'] ?>"/>
                    <?php endif; ?>

                    <?php if (strval($data['installed']) != '' and intval($data['installed']) != 0): ?><a class="btn btn-link text-dark p-0" href='<?php print admin_url() ?>view:modules/load_module:<?php print module_name_encode($data['module']) ?>'><?php endif; ?>
                        <div class="admin-modules-list-description mt-0">
                            <h6>
                                <?php if (isset($data['name'])): ?>
                                    <?php print _e($data['name']) ?>
                                <?php endif; ?>
                            </h6>
                        </div>
                        <?php if (strval($data['installed']) != '' and intval($data['installed']) != 0): ?></a><?php endif; ?>

                    <input type="hidden" name="id" value="<?php print $data['id'] ?>"/>
                    <input type="hidden" name="installed" value="<?php print $data['installed'] ?>"/>
                    <input type="hidden" name="ui" value="<?php print $data['ui'] ?>"/>
                    <input type="hidden" name="ui_admin" value="<?php print $data['ui_admin'] ?>"/>
                    <input type="hidden" name="position" value="<?php print $data['position'] ?>"/>
                </div>

                <?php if (user_can_destroy_module($data)): ?>
                <?php if (strval($data['installed']) != '' and intval($data['installed']) != 0): ?>
                    <button class="btn btn-link btn-sm text-danger btn-rounded btn-icon position-absolute module-uninstall-btn" data-toggle="tooltip" data-title="<?php _e("Uninstall"); ?>" name="uninstall" type="button" id="module_uninstall_<?php print $params['id']; ?>" data-module-name="<?php print $data['module'] ?>" data-module-id="<?php print$data['id'] ?>" value="đ"><i class="mdi mdi-close-thick"></i></button>
                    <!-- <span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert module-ctrl-btn"><?php _e("Open"); ?></span>-->
                <?php else: ?>
                    <button class="btn btn-outline-success btn-sm position-absolute module-ctrl-btn" name="install" type="button" id="module_install_<?php print $params['id']; ?>" data-module-name="<?php print $data['module'] ?>" data-module-id="<?php print $data['id'] ?>"><?php _e("Install"); ?></button>
                <?php endif; ?>
                <?php endif; ?>

            </form>
        </div>
    </div>
<?php endif; ?>

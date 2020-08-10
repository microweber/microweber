<?php include(__DIR__ . DS . 'header.php'); ?>

<?php if (is_admin() == false): ?>
    <module type="users/login" template="admin"/>
<?php else: ?>
    <?php $v1 = mw()->url_manager->param('load_module'); ?>
    <?php $v = mw()->url_manager->param('view');

    if ($v1 != false) {
        $holder_cls = mw()->url_manager->slug($v1);
    } else if ($v != false) {
        $holder_cls = mw()->url_manager->slug($v);
    } else {
        $holder_cls = false;
    }
    ?>

    <main class="module-<?php print  $holder_cls ?>">

        <?php if (isset($render_content) != false and $render_content): ?>
            <?php print $render_content ?>

        <?php elseif ($v1 != false): ?>
            <?php
            $v_mod = module_name_decode($v1);

            if (is_module($v_mod)) {
                ?>

                <?php
                $module_info = module_info($v_mod);
                $module_permissions = module_permissions($module_info);
                if (!user_can($module_permissions['index'])):
                    ?>
                    <div class="card card-danger mb-3">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="icon-title">
                                    <i class="mdi mdi-account-lock text-dark"></i> <h5 class="mb-0">Permission
                                        denied!</h5>
                                </div>
                            </div>
                            <p class="text-center">
                                You don't have permissions to see <b><?php echo $module_info['name']; ?></b> module. <br /><br />
                                <button onclick="window.history.back()" class="btn btn-outline-dark btn-sm"><i class="mdi mdi-chevron-double-left"></i> Back to modules</button>
                            </p>
                        </div>
                    </div>
                    <?php
                    return;
                endif;
                ?>

                <?php
                $mod = '<module type="' . $v_mod . '" view="admin"  backend="true" id="mw-main-module-backend" />';
                print $mod;
            } else {
                print "No module found {$v_mod}";
            }
            ?>
        <?php else: ?>
            <?php
            $vf = __DIR__ . DS . $v . '.php';
            $vf = str_replace('..', '', $vf);

            if (is_file($vf)) {
                include($vf);
            } else {
                $v_mod = module_name_decode($v);
                if ($v_mod == 'modules') {
                    $v_mod = 'admin/modules';
                }

                if ($v_mod != '' and is_module($v_mod)) {
                    // $mod = load_module($v_mod, $attrs=array('view' => 'admin','backend' => 'true'));

                    $mod = '<module type="' . $v_mod . '" view="admin"  backend="true" id="mw-main-module-backend" />';

                    print $mod;
                } else {
                    include(__DIR__ . DS . 'dashboard.php');
                }
            }
            ?>
        <?php endif; ?>

        <?php event_trigger('mw.admin.footer'); ?>

        <?php include(__DIR__ . DS . 'copyright.php'); ?>
    </main>

<?php endif; ?>

<?php include(__DIR__ . DS . 'footer.php'); ?>
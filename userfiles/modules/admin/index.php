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

    <div class="mw-ui-col admin-content-column <?php print  $holder_cls ?>">
        <?php if ($v1 != false): ?>
            <?php

            $v_mod = module_name_decode($v1);

            if (is_module($v_mod)) {
                $mod = '<module type="' . $v_mod . '" view="admin"  backend="true" id="mw-main-module-backend" />';
                print $mod;
            } else {
                print "No module found {$v_mod}";
            }

            ?>
        <?php else : ?>
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

            } ?>
        <?php endif; ?>
        <?php event_trigger('mw.admin.footer'); ?>
    </div>

<?php endif; ?>

<?php include(__DIR__ . DS . 'footer.php'); ?>

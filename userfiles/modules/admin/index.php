 
<?php include ($config["path_to_module"] . 'header.php'); ?>
<?php if(is_admin() == false): ?>
<module type="users/login" template="admin" />
<?php else: ?>
<?php $v1 = mw('url')->param('load_module'); ?>
<?php $v = mw('url')->param('view');

    if($v1 != false){
        $holder_cls = mw('url')->slug($v1);
    }  else if($v != false){
        $holder_cls = mw('url')->slug($v);
    } else {
        $holder_cls = false;
    }
    ?>

<div class="mw-ui-col admin-content-column <?php print  $holder_cls ?>">
  <?php if($v1 != false): ?>
  <?php

            $v_mod = module_name_decode($v1);

            if(is_module($v_mod)){
                $mod = '<module type="'.$v_mod.'" view="admin"  backend="true" id="mw-main-module-backend" />';
                print $mod ;
            } else {
                print "No module found {$v_mod}" ;
            }

            ?>
  <?php else : ?>
  <?php //include_once ($config["path_to_module"] . 'header_nav.php'); ?>
  <?php



            $vf = $config["path_to_module"] . $v. '.php';
            $vf = str_replace('..', '', $vf);

            if(is_file($vf)){
                //d($vf);

                include ($vf);

            }

            else { ?>
  <?php

                $v_mod = module_name_decode($v);

                if($v_mod != '' and is_module($v_mod)){
                    // $mod = load_module($v_mod, $attrs=array('view' => 'admin','backend' => 'true'));

                    $mod = '<module type="'.$v_mod.'" view="admin"  backend="true" id="mw-main-module-backend" />';

                    print $mod ;
                } else {

                    include ($config["path_to_module"] . 'index.php');
                }

            } ?>
  <?php endif; ?>
</div>
<?php endif; ?>
<?php	include ($config["path_to_module"] . 'footer.php'); ?>

<?php include (MW_ADMIN_VIEWS_DIR . 'header.php'); ?>

<?php/* var_dump(MW_ADMIN_VIEWS_DIR);*/ ?>
<?php if(is_admin() == false): ?>
<module type="users/login" template="admin" />
<?php else: ?>
<?php $v1 = mw()->url_manager->param('load_module'); ?>
<?php $v = mw()->url_manager->param('view');

  if($v1 != false){
	 $holder_cls = mw()->url_manager->slug($v1);
  }  else if($v != false){
	 $holder_cls = mw()->url_manager->slug($v);
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
   <?php



        $vf = MW_ADMIN_VIEWS_DIR . $v. '.php';
        $vf = sanitize_path($vf);

        if(is_file($vf)){
        //d($vf);

        include ($vf);

        }

         else { ?>
  <?php

        $v_mod = module_name_decode($v);

         if($v_mod != '' and is_module($v_mod)){


					   $mod = '<module type="'.$v_mod.'" view="admin"  backend="true" id="mw-main-module-backend" />';

			 print $mod ;
         } else {

             include (MW_ADMIN_VIEWS_DIR . 'index.php');
         }

         } ?>
  <?php endif; ?>
</div>
<?php endif; ?>
<?php	include (MW_ADMIN_VIEWS_DIR . 'footer.php'); ?>

<?php
    $module = get_modules_from_db('one=1&ui=any&module='.$v_mod);
 ?>

 <script>


 mwd.body.className += ' module-settings-page';

 </script>


<div id="mw_toolbar_nav" class="mw-small-toolbar">
    <a href="<?php print admin_url(); ?>view:dashboard" id="mw_logo_modules">
</a>
  <?php if(is_admin()): ?>
  <?php   $active = url_param('view'); ?>

    <a title="<?php _e("Back"); ?>" href="<?php print admin_url(); ?>view:modules" class="mw-ui-btn mw-btn-single-ico left back-to-admin-cookie"><span class="ico ilaquo"></span></a>
    <a href="<?php print admin_url(); ?>view:modules" class="mw-ui-btn right"><span><?php _e("Back"); ?></span></a>

    <div class="module-toolbar-info">
      <div class="admin-modules-list-image">
      <span class="mw_module_image_holder left">
        <?php if(isset($module['icon'])):  ?>
          <img src="<?php print $module['icon'] ?>" alt="<?php if(isset($module['name'])){ print $module['name']; }; ?> icon." />
        <?php endif; ?>
          <s class="mw_module_image_shadow"></s>
      </span>
      <span class="module-toolbar-info-description"><?php if(isset($module['name'])){ print $module['name']; }; ?></span>
      </div>
    </div>


  <?php endif; ?>
</div>

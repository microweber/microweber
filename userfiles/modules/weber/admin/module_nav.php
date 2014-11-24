<?php
    $module = mw('module')->get('one=1&ui=any&module='.$v_mod);
 ?>

<div id="mw-modules-toolbar">
  <?php if(is_admin()): ?>
  <div class="mw-ui-row-nodrop">
    <div class="mw-ui-col">
      <?php if(isset($module['icon'])):  ?>
      <img src="<?php print $module['icon'] ?>" alt="" />
      <?php endif; ?>
      <span class="module-toolbar-info-description">
      <?php if(isset($module['name'])){ print $module['name']; }; ?>
      </span> </div>
    <div class="mw-ui-col mw-modules-toolbar-back-icon"> <a title="<?php _e("Back"); ?>" href="<?php print admin_url(); ?>view:modules" class="pull-right"> <span class="mw-icon-back"></span> <span>
      <?php _e("Back"); ?>
      </span> </a> </div>
  </div>
  <?php  $active = mw('url')->param('view'); ?>
  <?php endif; ?>
</div>

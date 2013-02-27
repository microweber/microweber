<?
    $module = get_modules_from_db('one=1&ui=any&module='.$v_mod);
 ?>

 <script>


 mwd.body.className += ' module-settings-page';

 </script>


<div id="mw_toolbar_nav" class="mw-small-toolbar">
    <a href="<?php print admin_url(); ?>view:dashboard" id="mw_logo_modules">
</a>
  <? if(is_admin()): ?>
  <?   $active = url_param('view'); ?>

    <a title="<?php _e("Back"); ?>" href="<?php print admin_url(); ?>view:modules" class="mw-ui-btn-rect mw-btn-single-ico left"><span class="ico ilaquo"></span></a>
    <a href="javascript:;" class="mw-ui-btn-rect right"><span><?php _e("Buy now"); ?></span></a>

    <div class="module-toolbar-info">
      <div class="admin-modules-list-image">
      <span class="mw_module_image_holder left">
        <? if(isset($module['icon'])):  ?>
          <img src="<? print $module['icon'] ?>" alt="<? if(isset($module['name'])){ print $module['name']; }; ?> icon." />
        <? endif; ?>
          <s class="mw_module_image_shadow"></s>
      </span>
      <span class="module-toolbar-info-description"><? if(isset($module['name'])){ print $module['name']; }; ?></span>
      </div>
    </div>


  <? endif; ?>
</div>

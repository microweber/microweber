<?php only_admin_access(); ?>
<script  type="text/javascript">
$(document).ready(function(){
  mw.options.form('.<?php print $config['module_class'] ?>', function(){
      mw.notification.success("<?php _e("Advanced settings updated"); ?>.");
    });
});
</script>

<div class="<?php print $config['module_class'] ?>">
   <a class="mw-ui-btn" href="javascript:mw.clear_cache()"><?php _e("Clear cache"); ?></a>
   <a class="mw-ui-btn" href="javascript:api('mw_post_update'); void(0);"><?php _e("Reload Database"); ?></a>
   <a class="mw-ui-btn" href="javascript:mw.load_module('admin/notifications/system_log','#mw-advanced-settings-module-load-holder')"><?php _e("Show system log"); ?></a>
</div>
<div id="mw-advanced-settings-module-load-holder"></div>
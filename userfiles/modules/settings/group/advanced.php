<?php only_admin_access(); ?>
<script  type="text/javascript">
$(document).ready(function(){

  mw.options.form('.<?php print $config['module_class'] ?>', function(){
      mw.notification.success("<?php _e("Advanced settings updated"); ?>.");
    });
 

});
</script>
  
<div class="<?php print $config['module_class'] ?>">
   <a class="mw-ui-btn" href="javascript:mw.clear_cache()">Clear cache</a>
   <a class="mw-ui-btn" href="javascript:api('mw_post_update'); void(0);">Reload DB</a>
      <a class="mw-ui-btn" href="javascript:mw.load_module('admin/notifications/system_log','#mw-advanced-settings-module-load-holder')">Show system log</a>
</div>
<div id="mw-advanced-settings-module-load-holder">

</div>
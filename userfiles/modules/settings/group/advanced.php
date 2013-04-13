<? only_admin_access(); ?>
<script  type="text/javascript">
$(document).ready(function(){

  mw.options.form('.<? print $config['module_class'] ?>', function(){
      mw.notification.success("<?php _e("Advanced settings updated"); ?>.");
    });
 

});
</script>
  
<div class="<? print $config['module_class'] ?>">
   <a class="mw-ui-btn" href="javascript:mw.clear_cache()">Clear cache</a>
      <a class="mw-ui-btn" href="javascript:mw.clear_cache()">Show system log</a>
</div>

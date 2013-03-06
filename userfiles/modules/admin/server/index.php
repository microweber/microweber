<?php
only_admin_access();?>
 
 
<script  type="text/javascript">
$(document).ready(function(){
	
  mw.options.form('.<? print $config['module_class'] ?>', function(){
      mw.notification.success("<?php _e("server settings updated"); ?>.");
    });
});
</script>

<div class="<? print $config['module_class'] ?>">
<module="admin/server/cache_config" />
</div>

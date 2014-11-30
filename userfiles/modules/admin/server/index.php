<?php
only_admin_access();?>
 
 
<script  type="text/javascript">
$(document).ready(function(){
	
  mw.options.form('.<?php print $config['module_class'] ?>', function(){
      mw.notification.success("<?php _e("server settings updated"); ?>.");
    });
});
</script>

<div class="<?php print $config['module_class'] ?>">
<module="admin/server/cache_setup" />
</div>

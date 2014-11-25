<?php only_admin_access(); ?>
<script  type="text/javascript">
 mw.require('options.js');
 </script>
<script  type="text/javascript">


__whmcs_client_options_save_msg = function(){
	 if(mw.notification != undefined){
			 mw.notification.success('WHMCS settins are saved');
	 }
	 mw.reload_module("#whmcs_client_connection_status");
}

$(document).ready(function(){
    mw.options.form('#<?php print $params['id'] ?>', __whmcs_client_options_save_msg);
});




</script> 
<fieldset>
  <label><span>whmcs_url</span></label>
  <input name="whmcs_url"   class="mw_option_field"   type="text"  option-group="whmcs_client"  value="<?php print get_option('whmcs_url', 'whmcs_client') ?>">
</fieldset>
<fieldset>
  <label><span>whmcs_api_username</span></label>
  <input name="whmcs_api_username"   class="mw_option_field"   type="text"  option-group="whmcs_client"  value="<?php print get_option('whmcs_api_username', 'whmcs_client') ?>">
</fieldset>
<fieldset>
  <label><span>whmcs_api_key</span></label>
  <input name="whmcs_api_key"   class="mw_option_field"   type="text"  option-group="whmcs_client"  value="<?php print get_option('whmcs_api_key', 'whmcs_client') ?>">
</fieldset>
<module type="whmcs_client" view="views/connection_status" id="whmcs_client_connection_status" />
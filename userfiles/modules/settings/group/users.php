<script  type="text/javascript">
$(document).ready(function(){
	
  mw.options.form('.<? print $config['module_class'] ?>', function(){
      mw.notification.success("<?php _e("User settings updated"); ?>.");
    });
});
</script>

<div class="<? print $config['module_class'] ?>">
  <?   $curent_val = get_option('enable_user_registration','users'); ?>
  enable_user_registration
  <select name="enable_user_registration" class="mw_option_field mw-ui-field"   type="text" option-group="users">
    <option value="y" <? if($curent_val == 'y'): ?> selected="selected" <? endif; ?>>Yes</option>
    <option value="n" <? if($curent_val == 'n'): ?> selected="selected" <? endif; ?>>No</option>
  </select>
  <hr />
  <h2>facebook</h2>
  enable_user_fb_registration
  <?   $enable_user_fb_registration = get_option('enable_user_fb_registration','users');
 
 if($enable_user_fb_registration == false){
	$enable_user_fb_registration = 'n'; 
 }
  ?>
  <select name="enable_user_fb_registration" class="mw_option_field mw-ui-field"   type="text" option-group="users" data-refresh="settings/group/users">
    <option value="y" <? if($enable_user_fb_registration == 'y'): ?> selected="selected" <? endif; ?>>Yes</option>
    <option value="n" <? if($enable_user_fb_registration == 'n'): ?> selected="selected" <? endif; ?>>No</option>
  </select>
  <? if($enable_user_fb_registration == 'y'): ?>
  <br />  fb_app_id
  <input name="fb_app_id" class="mw_option_field mw-ui-field"   type="text" option-group="users"  value="<? print get_option('fb_app_id','users'); ?>" />
  <br />
  fb_app_secret
  <input name="fb_app_secret" class="mw_option_field mw-ui-field"   type="text" option-group="users"  value="<? print get_option('fb_app_secret','users'); ?>" />  <br />
  <? endif; ?>
  
  
</div>

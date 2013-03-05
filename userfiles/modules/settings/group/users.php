<script  type="text/javascript">
$(document).ready(function(){
	
  mw.options.form('.<? print $config['module_class'] ?>', function(){
      mw.notification.success("<?php _e("User settings updated"); ?>.");
    });
});
</script>

<div class="<? print $config['module_class'] ?>">
  <?   $curent_val = get_option('enable_user_registration','users'); ?>
  <label class="mw-ui-label">Enable User Registration</label>
  <div class="mw-ui-select">
    <select name="enable_user_registration" class="mw_option_field"   type="text" option-group="users">
      <option value="y" <? if($curent_val == 'y'): ?> selected="selected" <? endif; ?>>Yes</option>
      <option value="n" <? if($curent_val == 'n'): ?> selected="selected" <? endif; ?>>No</option>
    </select>
  </div>
  <div class="vSpace"></div>
  <hr>
  <h2>Facebook</h2>

  <label class="mw-ui-label">Enable Facebook Login </label>
  <?   $enable_user_fb_registration = get_option('enable_user_fb_registration','users');

 if($enable_user_fb_registration == false){
	$enable_user_fb_registration = 'n';
 }
  ?>
  <div class="mw-ui-select">
  <select name="enable_user_fb_registration" class="mw_option_field"   type="text" option-group="users" data-refresh="settings/group/users">
    <option value="y" <? if($enable_user_fb_registration == 'y'): ?> selected="selected" <? endif; ?>>Yes</option>
    <option value="n" <? if($enable_user_fb_registration == 'n'): ?> selected="selected" <? endif; ?>>No</option>
  </select>
  </div>



 <div class="vSpace"></div>
 <div class="vSpace"></div>
  <? if($enable_user_fb_registration == 'y'): ?>

  <label class="mw-ui-label-inline">Facebook App ID</label>
  <input name="fb_app_id" class="mw_option_field mw-ui-field mw-title-field" style="width: 380px;"   type="text" option-group="users"  value="<? print get_option('fb_app_id','users'); ?>" />
 <div class="vSpace"></div>
  <label class="mw-ui-label-inline">Facebook App secret</label>
  <input name="fb_app_secret" class="mw_option_field mw-ui-field mw-title-field"  style="width: 380px;"  type="text" option-group="users"  value="<? print get_option('fb_app_secret','users'); ?>" />  <br />
  <? endif; ?>
  

</div>

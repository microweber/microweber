<? only_admin_access(); ?>
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
  <ul class="mw-small-help">
    <li>Go here to set your api access <a target="_blank" href="https://developers.facebook.com/apps">https://developers.facebook.com/apps</a></li>
    <li>In <em>Website with Facebook Login</em> please enter <em><? print site_url() ?></em></li>
    <li>If asked for callback url - use <em><? print api_url('social_login_process?hauth.done=Facebook') ?></em></li>
  </ul>
  <? if($enable_user_fb_registration == 'y'): ?>
  <label class="mw-ui-label-inline">Facebook App ID</label>
  <input name="fb_app_id" class="mw_option_field mw-ui-field mw-title-field" style="width: 380px;"   type="text" option-group="users"  value="<? print get_option('fb_app_id','users'); ?>" />
  <div class="vSpace"></div>
  <label class="mw-ui-label-inline">Facebook App secret</label>
  <input name="fb_app_secret" class="mw_option_field mw-ui-field mw-title-field"  style="width: 380px;"  type="text" option-group="users"  value="<? print get_option('fb_app_secret','users'); ?>" />
  <? endif; ?>
  <div class="vSpace"></div>
  <div class="vSpace"></div>
  <h2>Google</h2>
  <label class="mw-ui-label">Enable Google Login </label>
  <?   $enable_user_google_registration = get_option('enable_user_google_registration','users');

 if($enable_user_google_registration == false){
	$enable_user_google_registration = 'n';
 }
  ?>
  <div class="mw-ui-select">
    <select name="enable_user_google_registration" class="mw_option_field"   type="text" option-group="users" data-refresh="settings/group/users">
      <option value="y" <? if($enable_user_google_registration == 'y'): ?> selected="selected" <? endif; ?>>Yes</option>
      <option value="n" <? if($enable_user_google_registration == 'n'): ?> selected="selected" <? endif; ?>>No</option>
    </select>
  </div>
  <div class="vSpace"></div>
  <div class="vSpace"></div>
  <? if($enable_user_google_registration == 'y'): ?>
  <ul class="mw-small-help">
    <li>Go here to set your api access <a target="_blank" href="https://code.google.com/apis/console/">https://code.google.com/apis/console/</a></li>
    <li>In redirect URI  please enter <em><? print api_url('social_login_process?hauth.done=Google') ?></em></li>
  </ul>
  <label class="mw-ui-label-inline">Client ID</label>
  <input name="google_app_id" class="mw_option_field mw-ui-field mw-title-field" style="width: 380px;"   type="text" option-group="users"  value="<? print get_option('google_app_id','users'); ?>" />
  <div class="vSpace"></div>
  <label class="mw-ui-label-inline">Client secret</label>
  <input name="google_app_secret" class="mw_option_field mw-ui-field mw-title-field"  style="width: 380px;"  type="text" option-group="users"  value="<? print get_option('google_app_secret','users'); ?>" />
  <? endif; ?>
  <div class="vSpace"></div>
  <div class="vSpace"></div>
  <h2>Github</h2>
  <label class="mw-ui-label">Enable Github Login </label>
  <?   $enable_user_github_registration = get_option('enable_user_github_registration','users');

 if($enable_user_github_registration == false){
  $enable_user_github_registration = 'n';
 }
  ?>
  <div class="mw-ui-select">
    <select name="enable_user_github_registration" class="mw_option_field"   type="text" option-group="users" data-refresh="settings/group/users">
      <option value="y" <? if($enable_user_github_registration == 'y'): ?> selected="selected" <? endif; ?>>Yes</option>
      <option value="n" <? if($enable_user_github_registration == 'n'): ?> selected="selected" <? endif; ?>>No</option>
    </select>
  </div>
  <div class="vSpace"></div>
  <div class="vSpace"></div>
  <? if($enable_user_github_registration == 'y'): ?>
  <ul class="mw-small-help">
    <li>Go here to register your application <a target="_blank" href="https://github.com/settings/applications/new">https://github.com/settings/applications/new</a></li>
    <li>In Main URL please enter <em><? print site_url() ?></em></li>
    <li>In Callback URL please enter <em><? print api_url('social_login_process?hauth.done=Github') ?></em></li>
  </ul>
  <label class="mw-ui-label-inline">Client ID</label>
  <input name="github_app_id" class="mw_option_field mw-ui-field mw-title-field" style="width: 380px;"   type="text" option-group="users"  value="<? print get_option('github_app_id','users'); ?>" />
  <div class="vSpace"></div>
  <label class="mw-ui-label-inline">Client secret</label>
  <input name="github_app_secret" class="mw_option_field mw-ui-field mw-title-field"  style="width: 380px;"  type="text" option-group="users"  value="<? print get_option('github_app_secret','users'); ?>" />
  <? endif; ?>
  <div class="vSpace"></div>
  <div class="vSpace"></div>
  
  
  <h2>Twitter</h2>
  <label class="mw-ui-label">Enable Twitter Login </label>
  <?   $enable_user_twitter_registration = get_option('enable_user_twitter_registration','users');

 if($enable_user_twitter_registration == false){
  $enable_user_twitter_registration = 'n';
 }
  ?>
  <div class="mw-ui-select">
    <select name="enable_user_twitter_registration" class="mw_option_field"   type="text" option-group="users" data-refresh="settings/group/users">
      <option value="y" <? if($enable_user_twitter_registration == 'y'): ?> selected="selected" <? endif; ?>>Yes</option>
      <option value="n" <? if($enable_user_twitter_registration == 'n'): ?> selected="selected" <? endif; ?>>No</option>
    </select>
  </div>
  <div class="vSpace"></div>
  <div class="vSpace"></div>
  <? if($enable_user_twitter_registration == 'y'): ?>
  <ul class="mw-small-help">
    <li>Go here to register your application <a target="_blank" href="https://dev.twitter.com/apps">https://dev.twitter.com/apps</a></li>
    <li>In Website please enter <em><? print site_url() ?></em></li>
    <li>In Callback URL please enter <em><? print api_url('social_login_process?hauth.done=Twitter') ?></em></li>
  </ul>
  <label class="mw-ui-label-inline">Consumer key</label>
  <input name="twitter_app_id" class="mw_option_field mw-ui-field mw-title-field" style="width: 380px;"   type="text" option-group="users"  value="<? print get_option('twitter_app_id','users'); ?>" />
  <div class="vSpace"></div>
  <label class="mw-ui-label-inline">Consumer secret</label>
  <input name="twitter_app_secret" class="mw_option_field mw-ui-field mw-title-field"  style="width: 380px;"  type="text" option-group="users"  value="<? print get_option('twitter_app_secret','users'); ?>" />
  <? endif; ?>
  <div class="vSpace"></div>
  <div class="vSpace"></div>
  
  
  
  
  
  
   <h2>Windows live</h2>
  <label class="mw-ui-label">Enable Windows live Login </label>
  <?   $enable_user_windows_live_registration = get_option('enable_user_windows_live_registration','users');

 if($enable_user_windows_live_registration == false){
  $enable_user_windows_live_registration = 'n';
 }
  ?>
  <div class="mw-ui-select">
    <select name="enable_user_windows_live_registration" class="mw_option_field"   type="text" option-group="users" data-refresh="settings/group/users">
      <option value="y" <? if($enable_user_windows_live_registration == 'y'): ?> selected="selected" <? endif; ?>>Yes</option>
      <option value="n" <? if($enable_user_windows_live_registration == 'n'): ?> selected="selected" <? endif; ?>>No</option>
    </select>
  </div>
  <div class="vSpace"></div>
  <div class="vSpace"></div>
  <? if($enable_user_windows_live_registration == 'y'): ?>
  <ul class="mw-small-help">
    <li>Go here to register your application <a target="_blank" href="https://manage.dev.live.com/ApplicationOverview.aspx">https://manage.dev.live.com/ApplicationOverview.asp</a></li>
    <li>In redirect domain please enter <em><? print site_url() ?></em></li>
    <li>In Callback URL please enter <em><? print api_url('social_login_process?hauth.done=Live') ?></em></li>
  </ul>
  <label class="mw-ui-label-inline">Client ID</label>
  <input name="windows_live_app_id" class="mw_option_field mw-ui-field mw-title-field" style="width: 380px;"   type="text" option-group="users"  value="<? print get_option('windows_live_app_id','users'); ?>" />
  <div class="vSpace"></div>
  <label class="mw-ui-label-inline">Client secret</label>
  <input name="windows_live_app_secret" class="mw_option_field mw-ui-field mw-title-field"  style="width: 380px;"  type="text" option-group="users"  value="<? print get_option('windows_live_app_secret','users'); ?>" />
  <? endif; ?>
  <div class="vSpace"></div>
  <div class="vSpace"></div>
  
  
  
  
</div>

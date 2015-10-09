<?php only_admin_access(); ?>
<script  type="text/javascript">



mw.require('forms.js', true);
mw.require('options.js', true);
</script>
<script  type="text/javascript">
$(document).ready(function(){

    mw.options.form('.<?php print $config['module_class'] ?>', function(){
      mw.notification.success("<?php _e("User settings updated"); ?>.");
    });


mw.tabs({
   tabs:'.group-logins',
   nav:'.social-providers-list li',
   toggle:false,
   onclick:function(tab, e){
     if(mw.tools.hasClass(e.target, 'mw-ui-check') || mw.tools.hasClass(e.target.parentNode, 'mw-ui-check')){
        mw.options.save(e.target.parentNode.querySelector('input'));
     }
     else{
       mw.$(".mw-icon- li").removeClass("active");
       if($(this).hasClass("active")){
         $(this.parentNode).addClass("active");
       }
       else{

       }
       $(this.parentNode).removeClass("active");
     }
   }
});


mw.$('.social-providers-list .mw-ui-check').bind('mousedown', function(){
    mw.tools.toggleCheckbox(this.querySelector('input'));
});


});
</script>
<style type="text/css">
.group-logins {
	display: none;
}
 [class*="mw-social-ico"] {
 cursor: pointer;
}
.social-providers-list {
	clear: both;
	padding-bottom: 20px;
	overflow: hidden;
}
.social-providers-list [class*='mw-icon-'] {
	font-size: 30px;
	margin-right: 0;
}
.social-providers-list .mw-icon-twitter {
	color: #55acee
}
.social-providers-list .mw-icon-facebook {
	color: #3B5999;
}
.social-providers-list .mw-icon-googleplus {
	color: #dd4b39;
}
.group-logins .mw-ui-label {
	padding-top: 20px;
}
</style>
<div class="<?php print $config['module_class'] ?>">
  <h2>
    <?php _e("Login & Register"); ?>
  </h2>
  <?php  $curent_val = get_option('enable_user_registration','users'); ?>
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">
      <?php _e("Enable User Registration"); ?>
    </label>
    <select name="enable_user_registration" class="mw-ui-field mw_option_field"   type="text" option-group="users">
      <option value="y" <?php if($curent_val == 'y'): ?> selected="selected" <?php endif; ?>>
      <?php _e("Yes"); ?>
      </option>
      <option value="n" <?php if($curent_val == 'n'): ?> selected="selected" <?php endif; ?>>
      <?php _e("No"); ?>
      </option>
    </select>
  </div>
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">
      <?php _e("Allow Social Login with"); ?>
    </label>
  </div>
  <?php

 $enable_user_fb_registration = get_option('enable_user_fb_registration','users');
 $enable_user_google_registration = get_option('enable_user_google_registration','users');
 $enable_user_github_registration = get_option('enable_user_github_registration','users');
 $enable_user_twitter_registration = get_option('enable_user_twitter_registration','users');
 $enable_user_microweber_registration = get_option('enable_user_microweber_registration','users');
 

$enable_user_windows_live_registration = get_option('enable_user_windows_live_registration','users');

$enable_user_linkedin_registration = get_option('enable_user_linkedin_registration','users');

 if($enable_user_fb_registration == false){
	$enable_user_fb_registration = 'n';
 }

 if($enable_user_google_registration == false){
	$enable_user_google_registration = 'n';
 }

 if($enable_user_github_registration == false){
    $enable_user_github_registration = 'n';
 }

 if($enable_user_twitter_registration == false){
    $enable_user_twitter_registration = 'n';
 }

 if($enable_user_windows_live_registration == false){
    $enable_user_windows_live_registration = 'n';
 }

 if($enable_user_microweber_registration == false){
    $enable_user_microweber_registration = 'n';
 }

 if($enable_user_linkedin_registration == false){
    $enable_user_linkedin_registration = 'n';
 }

 $form_show_first_name = get_option('form_show_first_name','users');

$form_show_last_name = get_option('form_show_last_name','users');

$form_show_address = get_option('form_show_address','users');

  ?>
  <ul class="social-providers-list mw-ui-btn-nav">
    <li class="mw-ui-btn mw-ui-btn-big active"> <span class="mw-icon-facebook login-tab-group active"></span> </li>
    <li class="mw-ui-btn mw-ui-btn-big"> <span class="mw-icon-googleplus login-tab-group"></span> </li>
    <li class="mw-ui-btn mw-ui-btn-big"> <span class="mw-icon-social-github login-tab-group"></span> </li>
    <li class="mw-ui-btn mw-ui-btn-big"> <span class="mw-icon-twitter login-tab-group"></span> </li>
     <li class="mw-ui-btn mw-ui-btn-big"> <span class="mw-icon-social-linkedin login-tab-group"></span> </li>

    <li class="mw-ui-btn mw-ui-btn-big"> <span class="mw-icon-mw login-tab-group"></span> </li>
    
  </ul>
  <div class="mw-ui-box mw-ui-box-content group-logins" style="display: block">
    <label class="mw-ui-check">
      <input type="checkbox" value="y" <?php if($enable_user_fb_registration == 'y'): ?> checked <?php endif; ?> name="enable_user_fb_registration" class="mw_option_field" option-group="users">
      <span></span> <span>Facebook login enabled?</span> </label>
    <hr>
    <ol class="ol">
      <li>
        <?php _e("Api access"); ?>
        <a class="mw-ui-link" target="_blank" href="https://developers.facebook.com/apps">https://developers.facebook.com/apps</a></li>
      <li>
        <?php _e("In"); ?>
        <em>
        <?php _e("Website with Facebook Login"); ?>
        </em>
        <?php _e("please enter"); ?>
        <em><?php print site_url(); ?></em></li>
      <li>
        <?php _e("If asked for callback url - use"); ?>
        <em><?php print api_link('social_login_process?provider=facebook') ?></em></li>
    </ol>
    <div class="mw-ui-field-holder">
      <label class="mw-ui-label">
        <?php _e("App ID/API Key"); ?>
      </label>
      <input name="fb_app_id" class="mw_option_field mw-ui-field mw-title-field "   type="text" option-group="users"  value="<?php print get_option('fb_app_id','users'); ?>" />
      <label class="mw-ui-label">
        <?php _e("App Secret"); ?>
      </label>
      <input name="fb_app_secret" class="mw_option_field mw-ui-field mw-title-field"   type="text" option-group="users"  value="<?php print get_option('fb_app_secret','users'); ?>" />
    </div>
  </div>
  <div class="mw-ui-box mw-ui-box-content group-logins">
    <label class="mw-ui-check">
      <input type="checkbox" value="y" <?php if($enable_user_google_registration == 'y'): ?> checked <?php endif; ?> name="enable_user_google_registration" class="mw_option_field" option-group="users">
      <span></span> <span>Google login enabled?</span> </label>
    <hr>
    <ol class="ol">
      <li>
        <?php _e("Set your"); ?>
        <em>
        <?php _e("Api access"); ?>
        </em> <a class="mw-ui-link" target="_blank" href="https://code.google.com/apis/console/">https://code.google.com/apis/console/</a></li>
      <li>
        <?php _e("In redirect URI  please enter"); ?>
        <em><?php print api_link('social_login_process?provider=google') ?></em></li>
    </ol>
    <div class="mw-ui-field-holder">
      <label class="mw-ui-label">
        <?php _e("Client ID"); ?>
      </label>
      <input name="google_app_id" class="mw_option_field mw-ui-field mw-title-field" style=""   type="text" option-group="users"  value="<?php print get_option('google_app_id','users'); ?>" />
      <label class="mw-ui-label">
        <?php _e("Client secret"); ?>
      </label>
      <input name="google_app_secret" class="mw_option_field mw-ui-field mw-title-field"  style=""  type="text" option-group="users"  value="<?php print get_option('google_app_secret','users'); ?>" />
    </div>
  </div>
  <div class="mw-ui-box mw-ui-box-content group-logins">
    <label class="mw-ui-check">
      <input type="checkbox" value="y" <?php if($enable_user_github_registration == 'y'): ?> checked <?php endif; ?> name="enable_user_github_registration" class="mw_option_field" option-group="users">
      <span></span> <span>Github login enabled?</span> </label>
    <hr>
    <ol class="ol">
      <li>
        <?php _e("Register your application"); ?>
        <a class="mw-ui-link" target="_blank" href="https://github.com/settings/applications/new">https://github.com/settings/applications/new</a></li>
      <li>
        <?php _e("In"); ?>
        <em>
        <?php _e("Main URL"); ?>
        </em>
        <?php _e("enter"); ?>
        <em><?php print site_url() ?></em></li>
      <li>
        <?php _e("In"); ?>
        <em>
        <?php _e("Callback URL"); ?>
        </em>
        <?php _e("enter"); ?>
        <em><?php print api_link('social_login_process?provider=github') ?></em></li>
    </ol>
    <label class="mw-ui-label">
      <?php _e("Client ID"); ?>
    </label>
    <input name="github_app_id" class="mw_option_field mw-ui-field mw-title-field" style=""   type="text" option-group="users"  value="<?php print get_option('github_app_id','users'); ?>" />
    <label class="mw-ui-label">
      <?php _e("Client secret"); ?>
    </label>
    <input name="github_app_secret" class="mw_option_field mw-ui-field mw-title-field"  style=""  type="text" option-group="users"  value="<?php print get_option('github_app_secret','users'); ?>" />
  </div>
  <div class="mw-ui-box mw-ui-box-content group-logins">
    <label class="mw-ui-check">
      <input type="checkbox" value="y" <?php if($enable_user_twitter_registration == 'y'): ?> checked <?php endif; ?> name="enable_user_twitter_registration" class="mw_option_field" option-group="users">
      <span></span> <span>Twitter login enabled?</span> </label>
    <hr>
    <ol class="ol">
      <li>
        <?php _e("Register your application"); ?>
        <a class="mw-ui-link" target="_blank" href="https://dev.twitter.com/apps">https://dev.twitter.com/apps</a></li>
      <li>
        <?php _e("In"); ?>
        <em>
        <?php _e("Website"); ?>
        </em>
        <?php _e("enter"); ?>
        <em><?php print site_url(); ?></em></li>
      <li>
        <?php _e("In"); ?>
        <em>
        <?php _e("Callback URL"); ?>
        </em>
        <?php _e("enter"); ?>
        <em><?php print api_link('social_login_process?provider=twitter') ?></em></li>
    </ol>
    <label class="mw-ui-label">
      <?php _e("Consumer key"); ?>
    </label>
    <input name="twitter_app_id" class="mw_option_field mw-ui-field mw-title-field" style=""   type="text" option-group="users"  value="<?php print get_option('twitter_app_id','users'); ?>" />
    <label class="mw-ui-label">
      <?php _e("Consumer secret"); ?>
    </label>
    <input name="twitter_app_secret" class="mw_option_field mw-ui-field mw-title-field"  style=""  type="text" option-group="users"  value="<?php print get_option('twitter_app_secret','users'); ?>" />
  </div>
  
  
  
  
  
  <div class="mw-ui-box mw-ui-box-content group-logins">
    <label class="mw-ui-check">
      <input type="checkbox" value="y" <?php if($enable_user_linkedin_registration == 'y'): ?> checked <?php endif; ?> name="enable_user_linkedin_registration" class="mw_option_field" option-group="users">
      <span></span> <span>Linked-in login enabled?</span> </label>
    <hr>
    <ol class="ol">
      <li>
        <?php _e("Register your application"); ?>
        <a class="mw-ui-link" target="_blank" href="https://www.linkedin.com/secure/developer">https://www.linkedin.com/secure/developer</a></li>
      <li>
        <?php _e("In"); ?>
        <em>
        <?php _e("Website"); ?>
        </em>
        <?php _e("enter"); ?>
        <em><?php print site_url(); ?></em></li>
      <li>
        <?php _e("In"); ?>
        <em>
        <?php _e("Callback URL"); ?>
        </em>
        <?php _e("enter"); ?>
        <em><?php print api_link('social_login_process?provider=linkedin') ?></em></li>
    </ol>
    <label class="mw-ui-label">
      <?php _e("Client ID"); ?>
    </label>
    <input name="linkedin_app_id" class="mw_option_field mw-ui-field mw-title-field" style=""   type="text" option-group="users"  value="<?php print get_option('linkedin_app_id','users'); ?>" />
    <label class="mw-ui-label">
      <?php _e("Client Secret"); ?>
    </label>
    <input name="linkedin_app_secret" class="mw_option_field mw-ui-field mw-title-field"  style=""  type="text" option-group="users"  value="<?php print get_option('linkedin_app_secret','users'); ?>" />
  </div>
  
  
  <div class="mw-ui-box mw-ui-box-content group-logins">
    <label class="mw-ui-check">
      <input type="checkbox" value="y" <?php if($enable_user_microweber_registration == 'y'): ?> checked <?php endif; ?> name="enable_user_microweber_registration" class="mw_option_field" option-group="users">
      <span></span> <span>Microweber login enabled?</span> </label>
    <hr>
    <label class="mw-ui-label">
      <?php _e("Client ID"); ?>
    </label>
    <input name="microweber_app_id" class="mw_option_field mw-ui-field mw-title-field" style=""   type="text" option-group="users"  value="<?php print get_option('microweber_app_id','users'); ?>" />
    <label class="mw-ui-label">
      <?php _e("Client secret"); ?>
    </label>
    <input name="microweber_app_secret" class="mw_option_field mw-ui-field mw-title-field"  style=""  type="text" option-group="users"  value="<?php print get_option('microweber_app_secret','users'); ?>" />
  </div>
  <hr>
  <script>

 showLoginURLSettings  = function(){
    var el = mwd.getElementById('user-login-urls-set');
    $(el).toggle();

    if(el.style.display == 'block'){
      mw.tools.scrollTo(el);
    }
 }

 $(document).ready(function(){
   mw.tabs({
     nav:".user-sign-setting-nav-item",
     tabs:".mw-user-fields-form-item",
     toggle:true
   })
 })

 </script> 
  <a href="javascript:;" class="mw-ui-btn user-sign-setting-nav-item">
  <?php _e("Users URL settings"); ?>
  </a> <a href="javascript:;" class="mw-ui-btn user-sign-setting-nav-item">
  <?php _e("Register settings"); ?>
  </a> <a href="javascript:;" class="mw-ui-btn user-sign-setting-nav-item"> Social links </a>
  <div id="user-login-urls-set" class="mw-user-fields-form-item" style="display:none;padding-top: 20px;">
    <div class="mw-ui-box mw-ui-box-content">
      <h3>
        <?php _e("Register URL"); ?>
      </h3>
      <p>
        <?php _e("You can set a custom url for the register page"); ?>
      </p>
      <?php $checkout_url = get_option('register_url', 'users');  ?>
      <input name="register_url"  class="mw_option_field mw-ui-field"   type="text" option-group="users"   value="<?php print get_option('register_url','users'); ?>" placeholder="<?php _e("Use default"); ?>"  />
      <h3>
        <?php _e("Login URL"); ?>
      </h3>
      <p>
        <?php _e("You can set a custom url for the login page"); ?>
      </p>
      <?php $checkout_url = get_option('login_url', 'users');  ?>
      <input name="login_url"  class="mw_option_field mw-ui-field"   type="text" option-group="users"   value="<?php print get_option('login_url','users'); ?>" placeholder="<?php _e("Use default"); ?>"  />
      <h3>
        <?php _e("Forgot password URL"); ?>
      </h3>
      <p>
        <?php _e("You can set a custom url for the forgot password page"); ?>
      </p>
      <?php $checkout_url = get_option('forgot_password_url', 'users');  ?>
      <input name="forgot_password_url"  class="mw_option_field mw-ui-field"   type="text" option-group="users"   value="<?php print get_option('forgot_password_url','users'); ?>" placeholder="<?php _e("Use default"); ?>"  />
    </div>
  </div>
  <div id="mw-user-fields-form-set" class="mw-user-fields-form-item" style="display:none;padding-top: 20px;">
    <div class="mw-ui-box mw-ui-box-content"> 
      <script  type="text/javascript">

 runRegisterMailEditor = function(){
    
        RegisterMailEditor = mw.editor({
            element:"#register_email_content",
            addControls:mwd.getElementById('register_mail_editorctrls').innerHTML,
            ready:function(content){
              content.defaultView.mw.dropdown();
              mw.$("#register_mail_dynamic_vals li", content).bind('click', function(){
                  RegisterMailEditor.api.insert_html($(this).attr('value'));
              });
            }
        });

      
 
}


  $(document).ready(function(){

runRegisterMailEditor();


 })
  </script>
       
        <h2>
          <?php _e("Send email on new user registration"); ?>
        </h2>
      <div class="mw-ui-field-holder">
      <label class="mw-ui-check" style="margin-right: 15px;">
        <input name="register_email_enabled" class="mw_option_field"    data-option-group="users"  value="1"  type="radio"  <?php if(get_option('register_email_enabled', 'users') == 1): ?> checked="checked" <?php endif; ?> >
        <span></span><span>
        <?php _e("Yes"); ?>
        </span></label>
      <label class="mw-ui-check">
        <input name="register_email_enabled" class="mw_option_field"     data-option-group="users"  value="0"  type="radio"  <?php if(get_option('register_email_enabled', 'users') != 1): ?> checked="checked" <?php endif; ?> >
        <span></span><span>
        <?php _e("No"); ?>
        </span></label>
        </div>
          <div class="mw-ui-field-holder">
        
              <label class="mw-ui-label">
                        <?php _e("Email subject"); ?>
                      </label>
                      <input name="register_email_subject" class="mw-ui-field mw_option_field"   id="order_email_subject"  placeholder="<?php _e("Thank you for your registration"); ?>!" data-option-group="orders"  value="<?php print get_option('register_email_subject', 'users') ?>"  type="text" />
        
          </div>
        
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">
          <?php _e("Email content"); ?>
        </label>
        <textarea class="mw-ui-field mw_option_field"  data-option-group="users" id="register_email_content" name="register_email_content"><?php print get_option('register_email_content', 'users') ?></textarea>
      </div>
      <div id="register_mail_editorctrls" style="display: none"> <span class="mw_dlm"></span>
        <div style="width: 112px;" data-value="" title="<?php _e("These values will be replaced with the actual content"); ?>" id="register_mail_dynamic_vals" class="mw-dropdown mw-dropdown-type-wysiwyg mw-dropdown-type-wysiwyg_blue mw_dropdown_action_dynamic_values"> <span class="mw-dropdown-value"> <span class="mw-dropdown-arrow"></span> <span class="mw-dropdown-val">
          <?php _e("E-mail Values"); ?>
          </span> </span>
          <div class="mw-dropdown-content">
            <ul>
              <li value="{id}"><a href="javascript:;">
               User ID
                </a></li>
              <li value="{username}"><a href="javascript:;">
            Username
                </a></li>
               
              <li value="{email}"><a href="javascript:;">
               Email
                </a></li>
              <li value="{first_name}"><a href="javascript:;">
               First Name
                </a></li>
                  <li value="{last_name}"><a href="javascript:;">
               Last Name
                </a></li>
                
                   <li value="{created_at}"><a href="javascript:;">
               Date of registration
                </a></li>
                 
            </ul>
          </div>
        </div>
      </div>
    </div>
    <hr>
    <?php  $captcha_disabled = get_option('captcha_disabled','users');     ?>
    <div class="mw-ui-box mw-ui-box-content">
    
    
    
        <h2>
          <?php _e("Register form settings"); ?>
        </h2>
    
    
    
      <label class="mw-ui-check">
        <input type="checkbox" class="mw_option_field"   option-group="users" name="captcha_disabled" <?php if($captcha_disabled == 'y'): ?> checked <?php endif; ?> value="y">
        <span></span><span>Disable Captcha?</span> </label>
      <hr>
      <label class="mw-ui-check">
        <input type="checkbox" class="mw_option_field"   option-group="users" name="form_show_first_name" <?php if($form_show_first_name == 'y'): ?> checked <?php endif; ?> value="y">
        <span></span><span>First name</span> </label>
      <br>
      <label class="mw-ui-check">
        <input type="checkbox" class="mw_option_field"   option-group="users" name="form_show_last_name" <?php if($form_show_last_name == 'y'): ?> checked <?php endif; ?> value="y">
        <span></span><span>Last name</span> </label>
    </div>
  </div>
  <div id="mw-global-fields-social-profile-set" class="mw-user-fields-form-item" style="display:none;padding-top: 20px;">
    <module type="social_links/admin" module-id="website" />
  </div>
</div>
</div>

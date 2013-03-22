<?php $form_btn_title =  get_option('form_btn_title', $params['id']);
		if($form_btn_title == false) { 
		$form_btn_title = 'Register';
		}
		
		
$enable_user_fb_registration =  get_option('enable_user_fb_registration', $params['id']);
if($enable_user_fb_registration == 'y') { 
$enable_user_fb_registration = true;
} else {
$enable_user_fb_registration = false;
}

if($enable_user_fb_registration == true){
	$enable_user_fb_registration_site =  get_option('enable_user_fb_registration', 'users');
	if($enable_user_fb_registration_site == 'y') { 
	$enable_user_fb_registration = true;
	
	$fb_app_id  = get_option('fb_app_id','users');
	$fb_app_secret  = get_option('fb_app_secret','users');
	
	if($fb_app_id != false){
	$fb_app_id = trim($fb_app_id);	
	}
	
	if($fb_app_secret != false){
	$fb_app_secret = trim($fb_app_secret);	
	}
	
	
	
	if($fb_app_id == ''){
	$enable_user_fb_registration = false;
	}
	
	} else {
	$enable_user_fb_registration = false;
	
	}
}


		
		 ?>
<? //$rand = uniqid(); ?>
<script  type="text/javascript">

mw.require('forms.js');
 

$(document).ready(function(){
	
	 
	 
	 mw.$('#user_registration_form{rand}').submit(function() { 

 
 mw.form.post(mw.$('#user_registration_form{rand}') , '<? print site_url('api') ?>/register_user', function(){
	 
	 
	// mw.reload_module('[data-type="categories"]');
	 // mw.reload_module('[data-type="pages"]');
	 });

 return false;
 
 
 });
 
});
</script>

<form class="form-horizontal" id="user_registration_form{rand}" method="post">
  <legend><?php print get_option('form_title', $params['id']) ?></legend>
  <div class="control-group">
    <label class="control-label" for="email">Email</label>
    <div class="controls">
      <input type="text"   name="email" placeholder="Email">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="password">Password</label>
    <div class="controls">
      <input type="password"   name="password" placeholder="Password">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="captcha" >Captcha</label>
    <div class="controls">


    <div class="input-prepend">
        <span style="width: 100px;background: white" class="add-on">
            <img class="mw-captcha-img" src="<? print site_url('api/captcha') ?>" onclick="this.src='<? print site_url('api/captcha') ?>'" />
        </span>
        <input type="text" class="mw-captcha-input" name="captcha">
    </div>

    </div>
  </div>
  <div class="control-group">
    <div class="controls"> 
      <!-- <label class="checkbox">
        <input type="checkbox">
        Remember me </label>-->

      <button type="submit" class="btn pull-left"><? print $form_btn_title ?></button>
      <p class="already-have-an-account">Already have an account? <a href="signin.html">Sign in</a></p>
    </div>
  </div>
</form>
<? if($enable_user_fb_registration == true): ?>


<? print $enable_user_fb_registration ?>


  <div id="fb-root"></div>
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId: '<?php echo $fb_app_id; ?>',

          
          oauth: true
        });
        FB.Event.subscribe('auth.login', function(response) {
          window.location.reload();
        });
        FB.Event.subscribe('auth.logout', function(response) {
          window.location.reload();
        });
      };
      (function() {
        var e = document.createElement('script'); e.async = true;
        e.src = document.location.protocol +
          '//connect.facebook.net/en_US/all.js';
        document.getElementById('fb-root').appendChild(e);
      }());
    </script>



 
<? endif; ?>

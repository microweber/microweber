<?php

/*

type: layout

name: Login default

description: Login default

*/

?>
<? $user = user_id(); ?>

<div id="mw-login">
  <? if($user != false): ?>
  <div>Welcome <? print user_name(); ?> </div>
  <a href="<? print site_url() ?>">Go to <? print site_url() ?></a> <a href="<? print site_url('api/logout') ?>" >Log Out</a>
  <? else:  ?>
  <div class="box-head">
    <h2>Login to your account</h2>
  </div>
  
  
  
  
 
  
  <? if(get_option('enable_user_fb_registration','users') =='y'): ?>
  <a class="facebook-login" href="<? print site_url('api/user_social_login?provider=facebook') ?>" >Sign in with Facebook</a>
       <? endif; ?>
 
  
  
  		<? if(get_option('enable_user_google_registration','users') =='y'): ?>
        <a href="<? print site_url('api/user_social_login?provider=google') ?>" >Google login</a>
       <? endif; ?>
       
       
       
       

 <? if(get_option('enable_user_github_registration','users') =='y'): ?>
       <a href="<? print site_url('api/user_social_login?provider=github') ?>" >Github login</a>
       <? endif; ?>
       
       
        <? if(get_option('enable_user_twitter_registration','users') =='y'): ?>
       <a href="<? print site_url('api/user_social_login?provider=twitter') ?>" >Twitter login</a>
       <? endif; ?>
       
         <? if(get_option('enable_user_windows_live_registration','users') =='y'): ?>
       <a href="<? print site_url('api/user_social_login?provider=live') ?>" >Windows live</a>
       <? endif; ?>
      

  <h2 class="section-title">
    <hr class="left visible-desktop">
    <span>or</span>
    <hr class="right visible-desktop">
  </h2>
  <form   method="post" id="user_login_{rand}"  action="<? print site_url('api/user_login') ?>"  >
    <div class="control-group">
      <input  class="mw-ui-field"  name="username" type="text" placeholder="<?php _e("Username"); ?>"   />
    </div>
    <div class="control-group">
      <input  class="mw-ui-field"  name="password" type="password" placeholder="<?php _e("Password"); ?>"   />
    </div>
    <div class="vSpace"></div>
    <input class="btn" type="submit" value="<?php _e("Login"); ?>" />
  </form>
  
  
  
  

  
  
  <? endif;  ?>
</div>

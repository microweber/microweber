<?php

/*

type: layout

name: Login default

description: Login default

*/

?>
<? $user = user_id(); ?>
<? $have_social_login = false; ?>

<div id="mw-login">
  <? if($user != false): ?>
  <module type="users/profile" />
  <? else:  ?>
  <div class="box-head">
    <h2>Login or <a href="javascript:mw.load_module('users/register', '#<? print $params['id'] ?>');">Register</a></h2>
  </div>

  <? if($have_social_login == true): ?>
  <h2 class="section-title">
    <hr class="left visible-desktop">
    <span>or</span>
    <hr class="right visible-desktop">
  </h2>
  <? endif; ?>
  <div id="user_login_holder_<? print $params['id'] ?>">
  <form   method="post" id="user_login_<? print $params['id'] ?>"  class="clearfix" action="#"  >
    <div class="control-group">
      <input  class="mw-ui-field large-field"   name="username" type="text" placeholder="<?php _e("Email"); ?>"   />
    </div>
    <div class="control-group" style="margin-bottom: 0;">
      <input  class="mw-ui-field large-field"  name="password" type="password" placeholder="<?php _e("Password"); ?>"   />
    </div>
    <a class="reset-password-link" href="javascript:mw.load_module('users/forgot_password', '#<? print $params['id'] ?>');">Forgot password?</a>

    <div class="vSpace"></div>



    <div class="social-login">
      <label>Login with</label>
        <? if(get_option('enable_user_fb_registration','users') =='y'): ?>
        <a href="<? print site_url('api/user_social_login?provider=facebook') ?>" class="mw-social-ico-facebook"></a>
        <? $have_social_login = true; ?>
        <? endif; ?>
        <? if(get_option('enable_user_twitter_registration','users') =='y'): ?>
        <a href="<? print site_url('api/user_social_login?provider=twitter') ?>" class="mw-social-ico-twitter"></a>
        <? $have_social_login = true; ?>
        <? endif; ?>
        <? if(get_option('enable_user_google_registration','users') =='y'): ?>
        <a href="<? print site_url('api/user_social_login?provider=google') ?>" class="mw-social-ico-google"></a>
        <? $have_social_login = true; ?>
        <? endif; ?>
        <? if(get_option('enable_user_windows_live_registration','users') =='y'): ?>
        <a href="<? print site_url('api/user_social_login?provider=live') ?>" class="mw-social-ico-live"></a>
        <? $have_social_login = true; ?>
        <? endif; ?>
        <? if(get_option('enable_user_github_registration','users') =='y'): ?>
        <a href="<? print site_url('api/user_social_login?provider=github') ?>" class="mw-social-ico-github"></a>
        <? $have_social_login = true; ?>
        <? endif; ?>


    </div>

    <input class="btn btn-large pull-right" type="submit" value="<?php _e("Login"); ?>" />


  </form>
  <div class="alert" style="margin: 0;display: none;"></div>
  </div>
  <? endif;  ?>

</div>

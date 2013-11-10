<?php

/*

type: layout

name: Login default

description: Login default

*/

?>
<?php $user = user_id(); ?>
<?php $have_social_login = false; ?>

<div id="mw-login">
  <?php if($user != false): ?>
  <div class="well">
    <module type="users/profile" />
  </div>
  <?php else:  ?>
  <div class="box-head">
    <h2><?php _e("Login or"); ?> <a href="javascript:mw.load_module('users/register', '#<?php print $params['id'] ?>');"><?php _e("Register"); ?></a></h2>
  </div>

  <?php if($have_social_login == true): ?>
  <h2 class="section-title">
    <hr class="left visible-desktop">
    <span>or</span>
    <hr class="right visible-desktop">
  </h2>
  <?php endif; ?>
  <div id="user_login_holder_<?php print $params['id'] ?>">
  <form   method="post" id="user_login_<?php print $params['id'] ?>"  class="clearfix" action="#"  >
    <div class="control-group">
      <input  class="mw-ui-field large-field"   name="username" type="text" placeholder="<?php _e("Email"); ?>"   />
    </div>
    <div class="control-group" style="margin-bottom: 0;">
      <input  class="mw-ui-field large-field"  name="password" type="password" placeholder="<?php _e("Password"); ?>"   />
    </div>
    <a class="reset-password-link" href="javascript:mw.load_module('users/forgot_password', '#<?php print $params['id'] ?>');"><?php _e("Forgot password"); ?>?</a>

    <div class="vSpace"></div>



    <div class="social-login">
      
        <?php if(get_option('enable_user_fb_registration','users') =='y'): ?>
        <a href="<?php print api_link('user_social_login?provider=facebook') ?>" class="mw-social-ico-facebook"></a>
        <?php $have_social_login = true; ?>
        <?php endif; ?>
        <?php if(get_option('enable_user_twitter_registration','users') =='y'): ?>
        <a href="<?php print api_link('user_social_login?provider=twitter') ?>" class="mw-social-ico-twitter"></a>
        <?php $have_social_login = true; ?>
        <?php endif; ?>
        <?php if(get_option('enable_user_google_registration','users') =='y'): ?>
        <a href="<?php print api_link('user_social_login?provider=google') ?>" class="mw-social-ico-google"></a>
        <?php $have_social_login = true; ?>
        <?php endif; ?>
        <?php if(get_option('enable_user_windows_live_registration','users') =='y'): ?>
        <a href="<?php print api_link('user_social_login?provider=live') ?>" class="mw-social-ico-live"></a>
        <?php $have_social_login = true; ?>
        <?php endif; ?>
        <?php if(get_option('enable_user_github_registration','users') =='y'): ?>
        <a href="<?php print api_link('user_social_login?provider=github') ?>" class="mw-social-ico-github"></a>
        <?php $have_social_login = true; ?>
        <?php endif; ?>


    </div>

    <input class="btn btn-large pull-right" type="submit" value="<?php _e("Login"); ?>" />

  <div class="alert" style="margin: 0;display: none;"></div>

  </form>
  </div>
  <?php endif;  ?>

</div>

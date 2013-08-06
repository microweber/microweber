<?php

  /*

    type: layout

    name: Mega

    description: Mega

  */

?>
<?php $user = user_id(); ?>
<?php $have_social_login = false; ?>

<script>
    gotoprofile = function(){
      window.location.href =  "<?php print mw_site_url() ?>profile";
    }
</script>
<div id="mw-login">
  <?php if($user != false): ?>
  <div class="well">
    <module type="users/profile" />
  </div>
  <?php else:  ?>
  <div class="box-head">
    <h2><?php _e("Login"); ?></h2>
  </div>
  <hr>
  <?php if($have_social_login == true): ?>
  <h2 class="section-title">
    <hr class="left visible-desktop">
    <span>or</span>
    <hr class="right visible-desktop">
  </h2>
  <?php endif; ?>
  <div id="user_login_holder_<?php print $params['id'] ?>">
  <form   method="post" id="user_login_<?php print $params['id'] ?>"  class="clearfix" action="#" data-callback="gotoprofile"  >
    <div class="control-group">
      <input  class="box" autofocus=""  name="username" type="text" placeholder="<?php _e("Email"); ?>"   />
    </div>
    <div class="control-group" style="margin-bottom: 0;">
      <input  class="box"  name="password" type="password" placeholder="<?php _e("Password"); ?>"   />
    </div>
    <a class="reset-password-link" href="javascript:mw.load_module('users/forgot_password', '#<?php print $params['id'] ?>', {template:'default'});"><?php _e("Forgot password"); ?>?</a>
    <div class="vSpace"></div>
    <div class="alert" style="display: none"></div>
    <div class="social-login">
      <label>Login with</label>
        <?php if(get_option('enable_user_fb_registration','users') =='y'): ?>
        <a href="<?php print mw_site_url('api/user_social_login?provider=facebook') ?>" class="mw-social-ico-facebook"></a>
        <?php $have_social_login = true; ?>
        <?php endif; ?>
        <?php if(get_option('enable_user_twitter_registration','users') =='y'): ?>
        <a href="<?php print mw_site_url('api/user_social_login?provider=twitter') ?>" class="mw-social-ico-twitter"></a>
        <?php $have_social_login = true; ?>
        <?php endif; ?>
        <?php if(get_option('enable_user_google_registration','users') =='y'): ?>
        <a href="<?php print mw_site_url('api/user_social_login?provider=google') ?>" class="mw-social-ico-google"></a>
        <?php $have_social_login = true; ?>
        <?php endif; ?>
        <?php if(get_option('enable_user_windows_live_registration','users') =='y'): ?>
        <a href="<?php print mw_site_url('api/user_social_login?provider=live') ?>" class="mw-social-ico-live"></a>
        <?php $have_social_login = true; ?>
        <?php endif; ?>
        <?php if(get_option('enable_user_github_registration','users') =='y'): ?>
        <a href="<?php print mw_site_url('api/user_social_login?provider=github') ?>" class="mw-social-ico-github"></a>
        <?php $have_social_login = true; ?>
        <?php endif; ?>
    </div>
   <span class="or-register">or <a href="javascript:mw.load_module('users/register', '#<?php print $params['id']; ?>', false, {template:'default'});"><?php _e("Register"); ?></a></span>
   <input class="btn btn-large pull-right" type="submit" value="<?php _e("Login"); ?>" />
  </form>
  </div>
  <?php endif;  ?>
</div>
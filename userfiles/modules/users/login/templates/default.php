<?php

/*

type: layout

name: Login default

description: Login default

*/

?>
<?php $user = user_id(); ?>
<?php $have_social_login = false; ?>

<script>mw.moduleCSS("<?php print modules_url(); ?>users/login/templates.css")</script>

<div id="mw-login" class="module-login well">
  <?php if($user != false): ?>
  <div>
    <module type="users/profile" />
  </div>
  <?php else:  ?>
  <div class="box-head">
    <h2><?php _e("Login or"); ?> <a href="<?php print register_url(); ?>"><?php _e("Register"); ?></a></h2>
  </div>

  <div id="user_login_holder_<?php print $params['id'] ?>">
  <form   method="post" id="user_login_<?php print $params['id'] ?>"  class="clearfix" action="#"  >
    <div class="control-group form-group">
      <input  class="large-field form-control"   name="username" type="text" placeholder="<?php _e("Email or username"); ?>"   />
    </div>
    <div class="control-group form-group">
      <input  class="large-field form-control"  name="password" type="password" placeholder="<?php _e("Password"); ?>"   />
    </div>
    <a class="reset-password-link" href="<?php print forgot_password_url(); ?>"><?php _e("Forgot password"); ?>?</a>

    
    <div class="alert" style="margin: 0;display: none;"></div>
    

    <div class="social-login">

    <?php
        # Login Providers
        $facebook = get_option('enable_user_fb_registration','users') =='y';
        $twitter = get_option('enable_user_twitter_registration','users') =='y';
        $google = get_option('enable_user_google_registration','users') =='y';
        $windows = get_option('enable_user_windows_live_registration','users') =='y';
        $github = get_option('enable_user_github_registration','users') =='y';

        if($facebook or $twitter or $google or $windows or $github){
           $have_social_login = true;
        }
        else{
          $have_social_login = false;
        }
    ?>

    <?php if($have_social_login){ ?>

        <h5><?php _e("Login with"); ?>:</h5>
    <?php } ?>

    <?php if($have_social_login){ ?><ul><?php } ?>
        <?php if($facebook): ?>
        <li><a href="<?php print api_link('user_social_login?provider=facebook') ?>" class="mw-signin-with-facebook">Facebook login</a></li>
        <?php endif; ?>
        <?php if($twitter): ?>
        <li><a href="<?php print api_link('user_social_login?provider=twitter') ?>" class="mw-signin-with-twitter">Twitter login</a></li>
        <?php endif; ?>
        <?php if($google): ?>
        <li><a href="<?php print api_link('user_social_login?provider=google') ?>" class="mw-signin-with-google">Google login</a></li>
        <?php endif; ?>
        <?php if($windows): ?>
        <li><a href="<?php print api_link('user_social_login?provider=live') ?>" class="mw-signin-with-live">Windows login</a></li>
        <?php endif; ?>
        <?php if($github): ?>
        <li><a href="<?php print api_link('user_social_login?provider=github') ?>" class="mw-signin-with-github">Github login</a></li>
        <?php endif; ?>
    <?php if($have_social_login){ ?></ul><?php } ?>

    </div>

    <input class="btn pull-right" type="submit" value="<?php _e("Login"); ?>" />



  </form>
  </div>
  <?php endif;  ?>

</div>

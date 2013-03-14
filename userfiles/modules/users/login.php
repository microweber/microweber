
<? $user = user_id(); ?>

<div id="mw-login">





  <? if($user != false): ?>
  <div>Welcome <? print user_name(); ?> </div>
  <a href="<? print site_url() ?>">Go to <? print site_url() ?></a> <a href="<? print site_url('api/logout') ?>" >Log Out</a>
  <? else:  ?>
  <div class="box-head">
    <h2>Login to your account</h2>
  </div>
  <span class="facebook-login">Sign in with Facebook</span>

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

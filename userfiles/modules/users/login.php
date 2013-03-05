
<? $user = user_id(); ?>

<div class="mw-o-box" id="mw-login">





  <? if($user != false): ?>
  <div>Welcome <? print user_name(); ?> </div>
  <a href="<? print site_url() ?>">Go to <? print site_url() ?></a> <a href="<? print site_url('api/logout') ?>" >Log Out</a>
  <? else:  ?>
  <form   method="post" id="user_login_{rand}"  action="<? print site_url('api/user_login') ?>"  >
    <div class="mw-ui-field-holder">
        <input  class="mw-ui-field"  name="username" type="text" placeholder="<?php _e("Username"); ?>"   />
    </div>
    <div class="mw-ui-field-holder">
        <input  class="mw-ui-field"  name="password" type="password" placeholder="<?php _e("Password"); ?>"   />
    </div>
    <div class="vSpace"></div>
    <input class="mw-ui-btn" type="submit" value="<?php _e("Login"); ?>" />
  </form>
  <? endif;  ?>
</div>

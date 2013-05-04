<?php

/*

type: layout

name: Login admin

description: Admin login style

*/

?>
<?php $user = user_id(); ?>

<div id="mw-login"> <span class="mw-logo left"></span> <span class="Beta">Beta Version</span>
  <div class="vSpace"></div>
  <div class="mw-o-box" id="admin_login">
    <?php if($user != false): ?>
    <div>Welcome <?php print user_name(); ?> </div>
    <a href="<?php print site_url() ?>">Go to <?php print site_url() ?></a> <a href="<?php print site_url('api/logout') ?>" >Log Out</a>
    <?php else:  ?>
    <form autocomplete="off" method="post" id="user_login_<?php print $params['id'] ?>"  action="<?php print site_url('api/user_login') ?>"  >
      <div class="mw-ui-field-holder">
        <input  class="mw-ui-field"  name="username" type="text" placeholder="<?php _e("Username"); ?>"   />
      </div>
      <div class="mw-ui-field-holder">
        <input  class="mw-ui-field"  name="password" type="password" placeholder="<?php _e("Password"); ?>"   />
      </div>
      <div class="mw-ui-field-holder"> <a href="javascript:mw.load_module('users/forgot_password', '#admin_login', false, {template:'admin'});" class="mw-ui-link left" style="margin-top:4px; ">Forgot my password?</a>
        <input class="mw-ui-btn right" type="submit" value="<?php _e("Login"); ?>" />
      </div>
    </form>
  </div>
  <?php endif;  ?>
</div>

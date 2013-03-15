<?php

/*

type: layout

name: Login admin

description: Admin login style

*/

?>
<? $user = user_id(); ?>

<div id="mw-login">
   <span class="mw-logo left"></span>
   <span class="Beta">Beta Version</span>
   <div class="vSpace"></div>
<div class="mw-o-box">
  <? if($user != false): ?>
  <div>Welcome <? print user_name(); ?> </div>
  <a href="<? print site_url() ?>">Go to <? print site_url() ?></a> <a href="<? print site_url('api/logout') ?>" >Log Out</a>
  <? else:  ?>

    <form autocomplete="off" method="post" id="user_login_{rand}"  action="<? print site_url('api/user_login') ?>"  >
      <div class="mw-ui-field-holder">

        <input  class="mw-ui-field"  name="username" type="text" placeholder="<?php _e("Username"); ?>"   />
      </div>
      <div class="mw-ui-field-holder">
        <input  class="mw-ui-field"  name="password" type="password" placeholder="<?php _e("Password"); ?>"   />
      </div>
      <div class="mw-ui-field-holder">
        <a href="javascript:;" class="mw-ui-link left" style="margin-top:4px; ">Forgot my password?</a>
        <input class="mw-ui-btn right" type="submit" value="<?php _e("Login"); ?>" />
      </div>
    </form>
  </div>

  <? endif;  ?>
</div>
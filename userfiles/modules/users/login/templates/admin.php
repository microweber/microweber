<?php

/*

type: layout

name: Login admin

description: Admin login style

*/

?>
<?php $user = user_id(); ?>

<div id="mw-login">

   <style type="text/css">
   body{
     background: #F4F4F4;
   }

   .mw-sign-version{
     font-size: 11px;
     color:#306587;
     white-space: nowrap;
     clear: both;
     float: right;
   }

   </style>





    <img src="<?php print INCLUDES_URL; ?>img/sign_logo.png" class="left" alt="" />


    <span class="mw-sign-version">Beta v. <?php print MW_VERSION; ?></span>



  <div class="vSpace"></div>
  <div class="mw-box" id="admin_login">
  <div class="mw-box-content">
    <?php if($user != false): ?>
    <div>Welcome <?php print user_name(); ?> </div>
    <a href="<?php print site_url() ?>">Go to <?php print site_url() ?></a> <a href="<?php print site_url('api/logout') ?>" >Log Out</a>
    <?php else:  ?>
    <form autocomplete="off" method="post" id="user_login_<?php print $params['id'] ?>"  action="<?php print site_url('api/user_login') ?>"  >
      <div class="mw-ui-field-holder">
        <input  class="mw-ui-field" autofocus="" tabindex="1"  name="username" type="text" placeholder="<?php _e("Username or Password"); ?>"   />
      </div>
      <div class="mw-ui-field-holder">
        <input  class="mw-ui-field"  name="password" tabindex="2" type="password" placeholder="<?php _e("Password"); ?>"   />
      </div>
      <div class="mw-ui-field-holder">
        <input class="mw-ui-btn right" type="submit" tabindex="3" value="<?php _e("Login"); ?>" />
      </div>
    </form>
  </div>
  </div>


  <a href="<?php print site_url() ?>" class="left"><span class="ico backico"></span>Back to My WebSite</a>


  <a href="javascript:mw.load_module('users/forgot_password', '#admin_login', false, {template:'admin'});" class="mw-ui-link right" style="margin-top:4px; ">Forgot my password?</a>

  <?php endif;  ?>
</div>

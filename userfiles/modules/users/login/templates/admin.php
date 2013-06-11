<?php

/*

type: layout

name: Login admin

description: Admin login style

*/

?>
<?php $user = user_id(); ?>

<div id="mw-login">
<script>mw.require("tools.js");</script>
<script>mw.require("session.js");</script>
<script>

 mw.session.checkPause = true;

$(document).ready(function(){
  mw.tools.dropdown();


  /* var lang = (navigator.language|| navigator.userLanguage).split("-")[0];
  mw.$("#lang_selector").setDropdownValue(lang+"asdasdsa") */

  mw.$("#lang_selector").bind("change", function(){
    mw.cookie.set("lang", $(this).getDropdownValue());
  });
})

</script>

   <style type="text/css">
   body{
     background: #F4F4F4;
   }

   .mw-sign-version{
     font-size: 11px;
     color:#306587;
     white-space: nowrap;
     clear: both;
   }

   </style>





   <div id="sign_logo_version">

    <a href="http://microweber.com" target="_blank"><img src="<?php print INCLUDES_URL; ?>img/sign_logo.png" alt="" /></a>

    <span class="mw-sign-version">Beta v. <?php print MW_VERSION; ?></span>

   </div>



  <div class="vSpace"></div>
  <div class="mw-box">
  <div class="mw-box-content" id="admin_login">
    <?php if($user != false): ?>
    <div>Welcome <?php print user_name(); ?> </div>
    <a href="<?php print site_url() ?>"><?php _e("Go to"); ?> <?php print site_url() ?></a> <a href="<?php print site_url('api/logout') ?>" ><?php _e("Log Out"); ?></a>
    <?php else:  ?>
    <form autocomplete="on" method="post" id="user_login_<?php print $params['id'] ?>"  action="<?php print site_url('api/user_login') ?>"  >
      <div class="mw-ui-field-holder">
        <input  class="mw-ui-field" autofocus="" tabindex="1" required  name="username" type="text" placeholder="<?php _e("Username or Email"); ?>" <?php if(isset($_REQUEST['username']) != false): ?> value="<?php print $_REQUEST['username'] ?>"  <?php endif;  ?>  />
      </div>
      <div class="mw-ui-field-holder">
        <input  class="mw-ui-field"  name="password" tabindex="2" required type="password" <?php if(isset($_REQUEST['password']) != false): ?> value="<?php print $_REQUEST['password'] ?>"  <?php endif;  ?> placeholder="<?php _e("Password"); ?>"   />
      </div>
      <div class="mw-ui-field-holder" style="margin: auto; width: 286px;">
        <span class="left" id="login_laguage_select"><span class="left"><?php _e("Language"); ?></span>


<div data-value="" title="" class="mw_dropdown mw_dropdown_type_wysiwyg" id="lang_selector">
    <span class="mw_dropdown_val_holder">
        <span class="dd_rte_arr"></span>
        <?php if(defined('MW_LANG') and MW_LANG != '' and MW_LANG != 'en'): ?>
         <span class="mw_dropdown_val"><?php print strtoupper(MW_LANG); ?></span>
         <?php else:  ?>
        <span class="mw_dropdown_val">EN</span>
        <?php endif;  ?>
    </span>
  <div class="mw_dropdown_fields">
    <ul>
<?php $langs = get_available_languages(); 
 

?>
<?php foreach($langs as $lang): ?>
<li value="<?php print $lang; ?>"><a href="javascript:;"><?php print strtoupper($lang); ?></a></li>
 <? endforeach; ?>
 
    </ul>
</div>
</div>



        </span>

        <input class="mw-ui-btn right" type="submit" tabindex="3" value="<?php _e("Login"); ?>" />
      </div>
    </form>
  </div>
  </div>


  <div class="vSpace"></div>

  <div id="login_foot">

    <a href="<?php print site_url() ?>" class="left"><span class="ico backico2"></span><?php _e("Back to My WebSite"); ?></a>
    <a href="javascript:mw.load_module('users/forgot_password', '#admin_login', false, {template:'admin'});" class="mw-ui-link right"><?php _e("Forgot my password"); ?>?</a>

  </div>

  <?php endif;  ?>
</div>

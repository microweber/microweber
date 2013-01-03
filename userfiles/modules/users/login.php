<? $form_id = "login_form_".crc32(url_string()).rand(); ?>
<? $user = user_id(); ?>

<div class="mw-o-box" id="mw-login">





  <? if($user != false): ?>
  <div>Welcome <? print user_name(); ?> </div>
  <a href="<? print site_url() ?>">Go to <? print site_url() ?></a> <a href="<? print site_url('api/logout') ?>" >Log Out</a>
  <? else:  ?>
  <form   method="post" id="<? print $form_id ?>"  action="<? print site_url('api/user_login') ?>"  >
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label"><?php _e("Username"); ?></label>
        <input  class="mw-ui-field"  name="username" type="text" default="Username or email"   />
    </div>
    <div class="mw-ui-field-holder">
      <label class="mw-ui-label"><?php _e("Password"); ?></label>
      <input  class="mw-ui-field"  name="password" type="password"   />
    </div>
    <div class="vSpace"></div>
    <input class="mw-ui-btn-rect" type="submit" value="Login"   />
  </form>
  <? endif;  ?>
</div>

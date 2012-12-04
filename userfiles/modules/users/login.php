<? $form_id = "login_form_".crc32(url_string()).rand(); ?>
<? $user = user_id(); ?>

<div class="mw-o-box">





  <? if($user != false): ?>
  <div>Welcome <? print user_name(); ?> </div>
  <a href="<? print site_url() ?>">Go to <? print site_url() ?></a> <a href="<? print site_url('api/logout') ?>" >Log Out</a>
  <? else:  ?>
  <form   method="post" id="<? print $form_id ?>"  action="<? print site_url('api/user_login') ?>"  >
    <input  class="mw-ui-field"  name="username" type="text" default="Username or email"   />
    <input  class="mw-ui-field"  name="password" type="password"   />
    <input class="mw-ui-btn-rect" type="submit" value="Login"   />
  </form>
  <? endif;  ?>
</div>

<? $form_id = "login_form_".crc32(url_string()).rand(); ?>
<? $user = user_id(); ?>

<div class="module-login">
  <? if($user != false): ?>
  <div>Welcome <? print user_name(); ?> </div>
  <a href="<? print site_url() ?>">Go to <? print site_url() ?></a> <a href="<? print site_url('api/logout') ?>" >Log Out</a>
  <? else:  ?>
  <form   method="post" id="<? print $form_id ?>"  action="<? print site_url('api/user_login') ?>"  >
    <input   name="username" type="text" default="Username or email"   />
    <input   name="password" type="password"   />
    <input type="submit" value="Login"   />
  </form>
  <? endif;  ?>
</div>

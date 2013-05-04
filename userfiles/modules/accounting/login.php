<?php

 
?>




<script>mw.require("<?php print $config['url_to_module'] ?>numia/style.css", true);</script>
<div class="numia-login">

<a class="numia_logo" href="http://numia.biz" target="_blank"></a>
<h2>Login to </h2>

<div class="well">
<form  method="post" id="numia_user_login"  action="<?php print $config['url_main'] ?>"  >
  <div class="control-group">
    <label><?php _e("Username"); ?></label>
    <input  class="mw-ui-field"  name="numia_username" type="text"   />
  </div>
  <div class="control-group">
  <label><?php _e("Password"); ?></label>
    <input  class="mw-ui-field"  name="numia_password" type="password"   />
  </div>
  <div class="vSpace"></div>
  <input class="btn btn-large" type="submit" value="<?php _e("Login"); ?>" />
 &nbsp;
  Or &nbsp;<a href="<?php print $config['url_main'] ?>?register">Register new user</a>
</form>

</div>

<div class="numia_powered_by">Powered by <a href="http://www.numia.biz" target="_blank">www.numia.biz</a></div>
</div>

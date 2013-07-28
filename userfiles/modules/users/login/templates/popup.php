<?php

/*

type: layout

name: Login Popup

description: Login Popup

*/

?>
<?php $user = user_id(); ?>
<?php $have_social_login = false; ?>

<div id="mw-login-popup">

  <div id="user_login_holder_<?php print $params['id'] ?>">
  <form   method="post" id="pop-up-login"  class="clearfix" action="#"  >
    <div class="mw-ui-field-holder">
      <input  class="mw-ui-field mw-ui-field-full" autofocus=""   name="username" type="text" placeholder="<?php _e("Email"); ?>"   />
    </div>
    <div class="mw-ui-field-holder" style="margin-bottom: 0;">
      <input  class="mw-ui-field mw-ui-field-full"  name="password" type="password" placeholder="<?php _e("Password"); ?>"   />
    </div>


    <div class="vSpace"></div>





    <input class="mw-ui-btn right" type="submit" value="<?php _e("Login"); ?>" />

  <div class="alert" style="margin: 0;display: none;"></div>

  </form>
  </div>


</div>

<script type="text/javascript">

$(document).ready(function(){

mw.$("#pop-up-login").submit(function(){


     mw.form.post($(this) , '<?php print mw_site_url('api/user_login') ?>', function(a, b){

			  mw.response('#user_login_<?php print $params['id'] ?>',this);
			 if(typeof this.success === 'string'){
				 mw.$("#session_modal").remove();
                 mw.$(".mw_overlay").remove();
                 mw.notification.success("<?php _e("You are now logged in"); ?>.");
                 return false;
			 }
             mw.notification.msg(this, 5000);
	 });


  return false;
});


})


</script>

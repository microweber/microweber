<? $form_id = "login_form_".md5(url()).rand(); ?>
<? $user = user_id();

 
?>

<div class="module-login">
  <? if($user > 0): ?>
  <h2>You are already logged in. </h2>
  <br />
  <a href="<? print site_url() ?>" class="after_login_link">Go to <? print site_url() ?></a> <br />
  <br />
  <a href="#"  onclick="mw.users.LogOut()"><strong>Log Out</strong></a>
  <? else:  ?>
  <script type="text/javascript">
 





 $(document).ready(function() {

			var <? print $form_id ?>options = {
    //target:     '#divToUpdate',
    url:        '<? print site_url('api/user/login') ?>',
	dataType: 'json',
    success:    function(resp) {
      if(resp.error != undefined){
		if(resp.error.login_error != undefined){
			 
			 $('#login_error_<? print $form_id ?>').html(resp.error.login_error);
			 $('#login_error_box_<? print $form_id ?>').show();
			 
		}
}
		if(resp.ok != undefined){
			window.location.href = '<? print site_url('dashboard') ?>'
		//window.location.reload();
		}
		
		if(resp.success != undefined){
			//window.location.href = '<? print site_url('dashboard') ?>'
		 window.location.reload();
		}

    }
};

// pass options to ajaxForm
$('#<? print $form_id ?>').ajaxForm(<? print $form_id ?>options);





        });


</script>
  <? /*
<a href="<? print site_url('fb_login'); ?>">Login with Facebook</a>
*/ ?>
  <form   method="post" id="<? print $form_id ?>" class="login_form form-horizontal"  >
    <?php if(!empty($user_login_errors)) : ?>
    <script>
            $(document).ready(function(){

                var err_string = '<ol class="submit-error-info"><?php foreach($user_login_errors as $k => $v) :  ?><li><?php print $v ?></li><?php endforeach; ?></ol>';
               if(modal != undefined){
			   modal.init({
                  html:err_string,
                  width:400,
                  height:200,
                  oninit:function(){

                  }
                })
			   } else {
				   alert(err_string);
			   }
            });
          </script>
    <?php endif ?>
    <div class="alert alert-error" style="display:none;"  id="login_error_box_<? print $form_id ?>">
      <!--<a class="close" data-dismiss="alert">x</a>-->
      <h4 class="alert-heading">Error!</h4>
      <div id="login_error_<? print $form_id ?>"> </div>
    </div
    
     >
    <fieldset>
      <legend>Login</legend>
      <div class="control-group">
        <label class="control-label" for="LoginUsername">Username</label>
        <div class="controls">
          <input tabindex="1" id="LoginUsername" class="type-text required login_username mw-input input-xlarge" name="username" type="text" default="Username or email" value="<?php print $form_values['username'];  ?>" />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="LoginPassword">Password</label>
        <div class="controls">
          <input tabindex="2" id="LoginPassword" class="type-text required login_password mw-input" name="password" type="password" default="Password" value="<?php print $form_values['password'];  ?>" />
        </div>
      </div>
      <input type="submit" value="Login" class="login-btn" />
    </fieldset>
  </form>
  <? endif;  ?>
</div>

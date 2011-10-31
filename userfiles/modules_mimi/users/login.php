<? $form_id = "login_form_".md5(url()).rand(); ?>

<? $user = user_id();

 
?>



<div class="login_engine">









<? if($user > 0): ?>



<h2>You are already logged in. </h2>

<br />
<br />
<a href="<? print site_url() ?>" class="after_login_link">Go to <? print site_url() ?></a>




<br />
<br />
<a href="#"  onclick="mw.users.LogOut()"><strong>Log Out</strong></a>




<? else:  ?>









  <script type="text/javascript">
 





 $(document).ready(function() {

			var <? print $form_id ?>options = {
    //target:     '#divToUpdate',
    url:        '<? print site_url('api/user/index') ?>',
	dataType: 'json',
    success:    function(resp) {
        //alert(resp.);
		if(resp.fail != undefined){
			Modal.box("<h2 style='padding:20px;text-align:center'>" + resp.fail + "</h2>", 300, 80);
            Modal.overlay()
		}

		if(resp.ok != undefined){
		//	window.location.href = '<? print site_url('dashboard') ?>'
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
  <h2 class="title"> Login </h2>
  <div class="c" >&nbsp;</div>
  <form   method="post" id="<? print $form_id ?>" class="login_form"  >
    <?php if(!empty($user_login_errors)) : ?>
    <script>
            $(document).ready(function(){

                var err_string = '<ol class="submit-error-info"><?php foreach($user_login_errors as $k => $v) :  ?><li><?php print $v ?></li><?php endforeach; ?></ol>';
                modal.init({
                  html:err_string,
                  width:400,
                  height:200,
                  oninit:function(){

                  }
                })
            });
          </script>
    <?php endif ?>
    <div class="login-item"> <span class="field">
      <input tabindex="1" id="LoginUsername" class="type-text required login_username" name="username" type="text" default="Username or email" value="<?php print $form_values['username'];  ?>" />
      </span>
      <!--<a style="padding-left: 12px" class="reg-help" href="<?php print site_url('users/user_action:forgotten_pass'); ?>"><small>Lost your username?</small></a> -->
    </div>
    <div class="c" style="padding-bottom: 15px;">&nbsp;</div>
    <div class="login-item"> <span class="field" style="margin-bottom: 0">
      <input tabindex="2" id="LoginPassword" class="type-text required login_password" name="password" type="password" default="Password" value="<?php print $form_values['password'];  ?>" />
      </span>
      <!-- <a style="padding-left: 12px" class="reg-help" href="<?php print site_url('users/user_action:forgotten_pass'); ?>"><small>Lost your password?</small></a> -->
    </div>
    <div class="clear" style="height: 10px;">&nbsp;</div>
    <div class="c">&nbsp;</div>
    <? /*
                   <span class="remember-me">
                <input type="checkbox" id="rememberme" class="left" style="margin: 0 5px 0 0" />
                <label for="rememberme" style="display: inline;padding: 0;">Remember me</label>
            </span>
                   */ ?>
    <input type="submit" value="Login" class="login_btn" />
    <? /*
      <div class="login_nr" style="float: left;width: 300px;margin:12px 0 0 15px;">

        <a href="<?php print site_url('users/user_action:register'); ?>" class="new_reg">Make new registration <u>click here</u></a> </div>
      */ ?>
  </form>
  <!--<div id="become_aff">
    <div id="become_aff_content"> Lorem Ipsum is simply dummy text of the printing and typesetting industry.
      Lorem Ipsum has been the industry's tandard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
      It has survived not only five centuries, but also the leap into electronic typesetting. </div>
    <a href="<? print site_url('dealers') ?>" class="btn right">Become a dealer</a> </div>-->
    
    
    
    
    
    <? endif;  ?>
    
    
    
    
    
    
    
    
</div>

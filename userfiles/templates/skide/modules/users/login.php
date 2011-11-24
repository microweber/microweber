
<? $form_id = "login_form_".md5(url()); ?>

<div class="login_engine">


<script type="text/javascript">
    function rememberMe(){
       if($("#rememberme").is(":checked")){
         var user = $("#LoginUsername").val();
         var pass = $("#LoginPassword").val();

         $.cookie("SOBUserPass", user + "," +  pass);

       }
    }

    $(document).ready(function(){
        var SOBUserPass = $.cookie("SOBUserPass");

    });







 $(document).ready(function() {



 if(window.location.href.indexOf('user_action:login')!=-1){
    $('#kids_login').die().unbind('click');
    $("#header_login_form").remove();
 }





			var <? print $form_id ?>options = {
    //target:     '#divToUpdate',
    url:        '<? print site_url('api/user/index') ?>',
	dataType: 'json',
    success:    function(resp) {
        //alert(resp.);
		if(resp.fail != undefined){
			mw.box.alert(resp.fail);
		}

		if(resp.ok != undefined){
			window.location.href = '<? print site_url('dashboard') ?>'
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






      <form method="post" id="<? print $form_id ?>" class="validate form">









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



           <div class="login_wrapper">
            <h2 style="padding-bottom: 16px">Login </h2>
           <div class="login-item">

               <span class="linput">
                    <input tabindex="1" id="LoginUsername" class="type-text required" name="username" type="text" default="Username or email" value="<?php print $form_values['username'];  ?>" />
                    <a class="reg-help" href="<?php print site_url('users/user_action:forgotten_pass'); ?>"><small>Lost your username?</small></a>
               </span>
           </div>
           <div class="login-item">

               <span class="linput">
                  <input tabindex="2" id="LoginPassword" class="type-text required" name="password" type="password" default="Password" value="<?php print $form_values['password'];  ?>" />
                  <a class="reg-help" href="<?php print site_url('users/user_action:forgotten_pass'); ?>"><small>Lost your password?</small></a>
               </span>
           </div>
          <div class="clear" style="height: 31px;">&nbsp;</div>



             <span class="lw_left">Login with</span>
             <a href="#" title="Login" class="the_login submit right">&nbsp;</a>

                <a href="#" class="fb_btn right" onclick="modal.ajax({file:'fblogin.php',width:450,height:220});">&nbsp;</a>


                <div class="c">&nbsp;</div>


                   <? /*
                   <span class="remember-me">
                <input type="checkbox" id="rememberme" class="left" style="margin: 0 5px 0 0" />
                <label for="rememberme" style="display: inline;padding: 0;">Remember me</label>
            </span>
                   */ ?>
             <input type="submit" value="" class="xhidden" />


<div class="login_nr">
  <p>Not registered yet?  </p>
  <a href="<?php print site_url('users/user_action:register'); ?>" class="new_reg">Make new registration click here</a>
</div>

      </div>

      </form>

   </div>

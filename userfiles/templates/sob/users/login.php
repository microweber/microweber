<?php //var_dump($form_values); ?>






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



</script>




<div class="main" style="height: 400px;">



<div class="border-bottom" id="top-inner-title" style="margin-bottom: 12px;">

   <h2>Login</h2>

</div>
      <form action="<?php print (getCurentURL()); ?>" method="post" id="logIn" class="validate" onsubmit="rememberMe();">

          <?php if(!empty($user_login_errors)) : ?>
          	<ol class="submit-error-info">
              	<?php foreach($user_login_errors as $k => $v) :  ?>
                  <li><?php print $v ?></li>
                  <?php endforeach; ?>
          	</ol>
          <?php endif ?>

           <div class="login-item" style="margin-right: 90px;">
               <label>Username or Email: <strong>*</strong></label>
               <span class="linput">
                    <input tabindex="1" id="LoginUsername" class="type-text required" name="username" type="text" value="<?php print $form_values['username'];  ?>" />
                    <a class="reg-help" href="<?php print site_url('users/user_action:forgotten_pass'); ?>">Lost your username?</a>
               </span>
           </div>
           <div class="login-item">
               <label>Password: <strong>*</strong></label>
               <span class="linput">
                  <input tabindex="2" id="LoginPassword" class="type-text required" name="password" type="password" value="<?php print $form_values['password'];  ?>" />
                  <a class="reg-help" href="<?php print site_url('users/user_action:forgotten_pass'); ?>">Lost your password?</a>
               </span>
           </div>
          <div class="clear" style="height: 31px;">&nbsp;</div>

            <span class="remember-me">
                <input type="checkbox" id="rememberme" />
                <label for="rememberme">Remember me</label>
            </span>
             <input type="submit" value="" class="hidden" />
            <a href="#" title="Login" class="btn submit">Log in</a>

            <!--or
            <a href="<?php print site_url('users/user_action:register'); ?>" class="ricon">Register</a>-->



      </form>
 </div>

<div class="sidebar">
  <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_people_online.php') ?>
  <div class="c" style="padding-bottom: 4px;"></div>
    <img src="<?php print TEMPLATE_URL; ?>img/login_add.jpg" alt="" />

</div>



  <?php //require (ACTIVE_TEMPLATE_DIR.'users/right_sidebar.php') ?>

<!--SUBNAV-->

<div id="RU-subnav">
  <div class="pad3"></div>
  <div id="RU-nav">
    <h1>Login</h1>
  </div>
  
  <!--HELP-->
  <div id="RU-help"> <a href="#" title="Help" class="help"></a> 
    
    <!--END HELP--> 
  </div>
  <div class="clr"></div>
  <!--END SUBNAV--> 
</div>
<div id="RU-content">
  <div class="pad2"></div>
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
  <div class="main border-top" style="padding-top: 15px">
  <div class="box-holder">
    <div class="box-top"></div>
    <div class="box-inside">
        <form action="<? print (getCurentURL()); ?>" method="post" id="logIn" class="validate" onsubmit="rememberMe();">
          <? if(!empty($user_login_errors)) : ?>
          <ol class="submit-error-info">
            <? foreach($user_login_errors as $k => $v) :  ?>
            <li><? print $v ?></li>
            <? endforeach; ?>
          </ol>
          <? endif ?>
          <div class="login-item" style="margin-right: 90px;">
            <label>Username or Email: <strong>*</strong></label>
            <span class="field">
                <input tabindex="1" id="LoginUsername" class="type-text required" name="username" type="text" value="<? print $form_values['username'];  ?>" />
            </span>
            <a class="reg-help" href="<? print site_url('users/user_action:forgotten_pass'); ?>">Lost your username?</a> </div>
          <div class="login-item">
            <label>Password: <strong>*</strong></label>
            <span class="field">
                <input tabindex="2" id="LoginPassword" class="type-text required" name="password" type="password" value="<? print $form_values['password'];  ?>" />
            </span>
            <a class="reg-help" href="<? print site_url('users/user_action:forgotten_pass'); ?>">Lost your password?</a> </div>
          <span class="remember-me">
          <input type="checkbox" id="rememberme" />
          <label for="rememberme">Remember me</label>
          </span> <a href="#" title="Login" class="btn submit">Log in</a>

          <!--or
                <a href="<? print site_url('users/user_action:register'); ?>" class="ricon">Register</a>-->
           <input type="submit" value="" class="abshidden" />
        </form>
       <div class="c" style="padding-bottom: 15px">&nbsp;</div>
    </div>

    <div class="box-bottom"></div>
  </div>

  </div>
  <div class="pad2"></div>
  <!--END CONTENT--> 
</div>

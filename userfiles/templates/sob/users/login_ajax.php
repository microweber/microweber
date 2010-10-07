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
<?php $back_to = $this->core_model->getParamFromURL ( 'back_to' );
if($back_to != ''){
$back_to = '/back_to:'. $back_to ;	
} else {
	$back_to  = false;
}
 ?>
 
  <div class="border-bottom" id="top-inner-title" style="margin-bottom: 12px; padding:6px 10px 6px 0;">
    <h2 style="">Login</h2>
  </div>
  <form action="<?php print (site_url('users/user_action:login')); ?><?php print $back_to; ?>" method="post" id="logIn_ajax" class="validate" onsubmit="rememberMe();">
    <?php if(!empty($user_login_errors)) : ?>
    <ol class="submit-error-info">
      <?php foreach($user_login_errors as $k => $v) :  ?>
      <li><?php print $v ?></li>
      <?php endforeach; ?>
    </ol>
    <?php endif ?>
    <div class="login-item" style="margin-right: 90px;">
      <label class="block" style="padding: 10px 0 5px 0">Username or Email: <strong>*</strong></label>
      <span class="linput">
      <input id="LoginUsername" class="type-text required" name="username" style="width: 265px;" type="text" value="<?php print $form_values['username'];  ?>" />
      </span> </div>
    <div class="login-item">
      <label class="block" style="padding: 10px 0 5px 0">Password: <strong>*</strong></label>
      <span class="linput">
      <input id="LoginPassword" class="type-text required" name="password" style="width: 265px;" type="password" value="<?php print $form_values['password'];  ?>" />
      <a class="reg-help" href="<?php print site_url('users/user_action:forgotten_pass'); ?>">Lost your password?</a> </span> </div>
    <div class="clear" style="height: 31px;">&nbsp;</div>
    <span class="remember-me">
    <input type="checkbox" id="rememberme" />
    <label for="rememberme">Remember me</label>

    </span><br />
<br />

    <input type="submit" value="login" class="hidden" />

    <a href="#" title="Login" class="btn left" onclick="$(this).parent().submit()"><span class="btn-right"><samp class="btn-mid">Log in</samp></span></a>

    <a href="<?php print site_url('users/user_action:register'); ?>" class="left" style="margin:2px 0 0 8px;">Register</a>
  </form>
 

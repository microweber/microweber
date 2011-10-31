<?php

/*

type: layout

name: members layout

description: members site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>
<? $user = user_id(); 
 
 //p($user );
 ?>
<script>
 function handle_form_sub_process_reg(){
	 
	mw.users.register('#regform', process_reg);  
 }
 
  function handle_form_sub_process_login(){
	 
	mw.users.login('#login_form', process_login);  
 }
 
  function process_login(msg){
	 if(msg.error){
	 $errors = 'Error' + '\n';
			var err = msg.error;
			$.each(err, function(key, value) {
			$errors = $errors+ '\n' + value;
			});
			alert($errors);
			
	
	 } else {
		 window.location.reload()
		 
		 
	 }
	 
 }
 
 function process_reg(msg){
	 //alert(msg.error);
	  
	 if(msg.error){
	 $errors = 'Error' + '\n';
		
			var err = msg.error;
			
			$.each(err, function(key, value) {
			$errors = $errors+ '\n' + value;
			});
			
			alert($errors);
			
	
	 } else {
		 window.location.reload()
		 
		 
	 }
	 
 }
						
						
		
 
 </script>

<div id="content" class="TheContent">
  <div <? if(intval($user) == 0): ?> class="TheContent1" id="register" <? endif; ?>>
    <? if(intval($user) == 0): ?>
    <div class="pad3">
      <div id="reg_left">
        <h2>Why to register?</h2>
        <div class="c" style="padding-bottom: 10px;">&nbsp;</div>
        <img src="<? print TEMPLATE_URL ?>img/logoreg.png" alt="" />
        <div class="c" style="padding-bottom: 10px;">&nbsp;</div>
        <p> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. </p>
        <div class="c" style="padding-bottom: 20px;">&nbsp;</div>
        <h2 class="left">I have registration </h2>
        <div class="c" style="padding-bottom: 10px;">&nbsp;</div>
        <form method="post" action="#" id="login_form" name="login_form" onsubmit="handle_form_sub_process_login(); return false;">
          <div class="field black">
            <label class="black">Login email or username</label>
            <input type="text" name="email" class="required-email" />
          </div>
          <div class="field black">
            <label class="black">Login password</label>
            <span>
            <input type="password" name="password" class="required" />
            </span> </div>
         <input type="submit"   style="display:none;" />
          <div class="c" style="padding-bottom: 10px;">&nbsp;</div>
          <a href="javascript: handle_form_sub_process_login();" class="sitebtn sitebtn_blue right"><span>LOGIN</span></a>
        </form>
      </div>
      <div id="reg_right">
        <h2>New registration</h2>
        <div class="c" style="padding-bottom: 10px;">&nbsp;</div>
        <form method="post" action="#" id="regform" name="regform" onsubmit="handle_form_sub_process_reg(); return false;">
          <div id="reg">
            <div id="regbox">
              <div class="field">
                <label>Your E-mail</label>
                <span>
                <input type="text" name="email" class="required-email" />
                </span> </div>
              <div class="field">
                <label>Your Name</label>
                <span>
                <input type="text" name="name" class="required" />
                </span> </div>
              <div class="field">
                <label>Choose Password</label>
                <span>
                <input type="password" name="password" class="required" />
                </span> </div>
              <div class="field"> <br />
                <label>Enter the text below</label>
                <img src="<? print site_url('captcha')?>" id="captcha" /><br/>
                <!-- CHANGE TEXT LINK -->
                <a href="#" onclick="
    document.getElementById('captcha').src='<? print site_url('captcha')?>/'+Math.random();
    document.getElementById('captcha-form').focus();"
    id="change-image">Not readable? Change text.</a> <span>
                <input type="text" name="captcha" id="captcha-form" />
                </span> <br />
                <br />
              </div>
            </div>
          </div>
          <div class="c" style="padding-bottom: 12px;">&nbsp;</div>
          <input type="checkbox" class="required" />
          <span class="i-agree">I agree with the terms and conditions</span>
          <div class="c" style="padding-bottom: 10px;">&nbsp;</div>
          <input type="submit"   style="display:none;" />
          <a class="sitebtn sitebtn_darkblue right" href="javascript: handle_form_sub_process_reg();"><span>Register new</span></a>
        </form>
      </div>
    </div>
    <? else: ?>
    <? $view = url_param('view'); ?>
    <? include('user_home.php'); ?>
    <? endif; ?>
    <div class="c">&nbsp;</div>
    <br />
    <br />
    <br />
    <br />
    <div id="footer">
      <address>
      <a href="#">Conditions of Use</a> | <a href="#">Privacy Notice</a> &copy; 1999-2011, <a href="http://asiaoilworks.com">AsiaOilWorks.com</a>, or its affiliates | Powered by <a href="http://microweber.com" title="Microweber">Microweber</a> | Design by <a href="http://ooyes.net" title="Web Design">OoYes.net</a>
      </address>
    </div>
  </div>
  <? if(intval($user) == 0): ?>
  <div class="TheContentTop">&nbsp;</div>
  <? endif; ?>
  <div class="TheContentBottom">&nbsp;</div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>

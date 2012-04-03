
<div class="page_tit">Forgotten password?</div>
 
<div class="reg_box">
  <script>
     
     
    function send_p(){
   
	    $email = jQuery( "#new_user_email" ).val();

	
    
    jQuery.post("<? print site_url('api/user/forgot_pass') ?>", { email: $email },
    function(resp) {
    if(resp.error != undefined){
		resp_msg = '';
		if(isobj(resp.error) != false){
jQuery.each(resp.error, function(i, val) {
      //$("#" + i).append(document.createTextNode(" - " + val));
	  resp_msg = resp_msg + '<br />' + val;
    });	

mw.box.alert(resp_msg);
	}
		
   // alert("Registration failed! Please check the catcha field.");
    }
    if(resp.success != undefined){
     window.location.href = '<? print site_url('dashboard') ?>' 
   // alert("Registration completed. The page will reload now.");
   // window.location.reload();
    }
    }, 'json');
    }
     
     
     
     
     
     
    </script>
  <div class="reg_top"></div>
  <div class="reg_mid">
    <div class="reg_logo"><img src="<? print TEMPLATE_URL ?>images/reg_ogo.png" alt="logo" /></div>
    <div class="reg_form_tit">Send Password Form</div>
    <div class="reg_form_box">
       <input type="text" class="reg_text_Box" value="Your E-mail" id="new_user_email"  />
      
      <div class="register_but">
        <a href="javascript:send_p()" class="btn_orange">Send me my password</a>
      </div>
    </div>
  </div>
  <div class="reg_bot"></div>
</div>
<div class="reg_text"><a href="<? print site_url('user-login') ?>">Click here</a> to login</div>

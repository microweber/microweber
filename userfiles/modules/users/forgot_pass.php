<script>
     
     
    function send_forgot_pass(){
   
	    $email = $( "#new_user_email" ).val();

	
    
    $.post("<? print site_url('api/user/forgot_pass') ?>", { email: $email },
    function(resp) {
		mw.box.alert(resp);
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
    }, 'html');
    }
     
     
     
     
     
     
    </script>

<div class="module-forgot-pass">
  <div class="login-item">
    <label>Enter your email:</label>
    <span class="field">
    <input type="text" class="forgot-pass-text-box mw-input"   id="new_user_email"  />
    </span> </div>
  <br />
  <a href="javascript:send_forgot_pass()" class="forgot-pass-btn mw-button">Send me my password</a> </div>

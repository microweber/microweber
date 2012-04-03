
<div class="page_tit">In order to use the site you must verify your email address</div>
 
<div class="reg_box">
  <script>
     
     
    function send_p(){
   
	    $email = jQuery( "#new_user_email" ).val();

	
    
    jQuery.post("<? print site_url('api/user/activate_link') ?>", { email: $email },
    function(resp) {
		
		
		alert(resp);
		
		
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
    
    <? //p($user_data); ?>
    
  <div class="reg_top"></div>
  <div class="reg_mid">
    <h2>Send email verification link</h2>
    <div class="reg_form_box">
       <input type="text" class="reg_text_Box" value="<? print $user_data['email'] ?>" id="new_user_email"  />
      
      <div class="register_but">
        <a href="javascript:send_p()" class="btn_orange">Send me my verification link</a>
      </div>
    </div>
  </div>
  <div class="reg_bot"></div>
</div>
 
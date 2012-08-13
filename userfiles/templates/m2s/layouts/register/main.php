


<editable rel="page" field="conent_body">
<div class="page_tit">New Registration</div>
<div class="contactus_text">Welcome to Money2Study.com, the online Student Community for everything Student.
<br />
 To use this site you need to register, it only takes a few seconds with just a few clicks, you become a valuable community member, enjoy! Thank you!</div>
 </editable>
 
 
 
<div class="reg_box">
  <script>
     
     
    function register(){
    $user = jQuery( "#new_user_username" ).val();
    $pass = jQuery( "#new_user_password" ).val();
	
	
	
	    $email = jQuery( "#new_user_email" ).val();
		 $uni = jQuery( "#custom_field_university" ).val();

  

 $i_agree = $('#i_agree').is(':checked'); 
 
	if($i_agree  == false ){
		
			mw.box.alert('You must agree to the terms and conditions first');
		
	} else {
	
	
	
	jQuery( ".reg_form_box_inside" ).hide();
	
	jQuery( ".reg_form_box_inside2" ).fadeIn();
	
				$captcha = jQuery( "#new_user_captcha" ).val();
				jQuery.post("<? print site_url('api/user/register') ?>", { username: $user ,email: $email , password: $pass ,custom_field_university:  $uni , captcha: $captcha},
				function(resp) {
					
					
						
				
					
					
				if(resp.error != undefined){
					
					
					jQuery( ".reg_form_box_inside2" ).hide();
				
				jQuery( ".reg_form_box_inside" ).fadeIn();
				
				
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
	
	
	
	
	
	
	
	
	
    }
     
     
     
	 

     
     
    </script>
  <div class="reg_top2"></div>
  <div class="reg_mid">
    <!--      <div class="reg_logo"><img src="<? print TEMPLATE_URL ?>images/reg_ogo.png" alt="logo" /></div>
-->
    <div class="reg_form_tit2">Registration Form</div> 
    <div class="reg_form_box">
    
    
    <div class="reg_form_box_inside">
    
      <input type="text" class="reg_text_Box" id="new_user_username"  title="Username" />
      <input type="password" class="reg_text_Box" title="Choose password" id="new_user_password"  />
      <input type="text" class="reg_text_Box" title="Your E-mail" id="new_user_email"  />
       
       
            <label for="custom_field_university" class="sm_label">Please enter the name of your current University, if you are an Alumni, please enter your past University. If you are joining as a supporter of the site, please leave this blank if you do not have a University you attended.</label>

            <input type="text" class="reg_text_Box" title="Your university?" name="custom_field_university" id="custom_field_university"  />
<br /><br />


      
      
      <!--	<div class="checkbox"><input type="checkbox" /></div>-->
      <img src="<? print site_url('captcha')?>" id="captcha" /><br/>
      <a href="javascript:document.getElementById('captcha').src='<? print site_url('captcha')?>/'+Math.random(); void(0)"
    id="change-image">Not readable? Change text.</a> <br />
      <label for="new_user_captcha">Enter the text below</label>
      <input type="text" class="reg_text_Box" title="Enter the text from the image" id="new_user_captcha"  />
      
      
      <br />
<br />

      
 
       <label for="i_agree"><input type="checkbox" name="i_agree" id="i_agree" value="yes" />I agree to the <a href="<? print site_url('terms-and-conditions') ?>" target="_blank">Terms & Conditions</a> of usage</label>
       <br />  <br />

      
<!--      <div class="i_agree">I agree with <span class="giving_orange"><a href="<? print site_url('terms-and-conditions') ?>">the terms</a> and </span></div>
-->      
      
      
      
      <div class="register_but">
        <input type="image" src="<? print TEMPLATE_URL ?>images/register_but.jpg" onclick="register();" />
      </div>
       
      </div>
      
      <div class="reg_form_box_inside2" style="display:none;">
      <h2>Processing, please wait...</h2>
      
       </div>
      
      
    </div>
  </div>
  <div class="reg_bot"></div> 
</div>
<div class="reg_text">If you have already registered, please <a href="<? print site_url('user-login') ?>"><strong>click here to login</strong></a> </div>
<br />

<div class="reg_text2">
<editable rel="page" field="custom_field_rtext_bot">
Money2Study allows 3 types of users to register; Current HE Students, Alumni and finally, what we call supporters. Each user according to the status of where they are in the Academic process has limited access to certain parts of the site. This ensures that current students in HE, get to use the site in a closed community knowing that if they meet anyone or interact, the users comes from a similar background, i.e. are fellow Students. This also allows
</editable>
</div>
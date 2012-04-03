<script>
	 
	 
	 function select_type($sel){
		 $( ".hide" ).hide()
		  $( ".profile_blk" ).show()
		 
		  $( $sel ).show()
		  
	 }
	 
	 
	</script>
<script>
     
     
    function register(){
    $user = jQuery( "#new_user_username" ).val();
    $pass = jQuery( "#new_user_password" ).val();
	
	$pass2 = jQuery( "#new_user_password2" ).val();
	
	    $email = jQuery( "#new_user_email" ).val();
		 $uni = jQuery( "#new_user_role" ).val();

  

 $i_agree = $('#i_agree').is(':checked'); 
 
 
 
 
 
 if( $pass   != $pass2 ){
		
			mw.box.alert('Password and Confirm password fields must be the same');
			
			
			
			
			
			
			
			
			
		
	} else {
		
		
	
 
 
 
 
 
 
 
 
 
 
	if($i_agree  == false ){
		
			mw.box.alert('You must agree to the terms and conditions first');
			
			
			
			
			
			
			
			
			
		
	} else {
	
	
	
	jQuery( ".reg_form_box_inside" ).hide();
	
	jQuery( ".reg_form_box_inside2" ).fadeIn();
	
				$captcha = jQuery( "#new_user_captcha" ).val();
				jQuery.post("<? print site_url('api/user/register') ?>", { username: $user ,email: $email , password: $pass ,role:  $uni , captcha: $captcha},
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
	
	
	
    }
     
     
     
	 
	 
	       $(document).ready(function(){
	 $(':input[title]').each(function() {
  var $this = $(this);
  if($this.val() === '') {
    $this.val($this.attr('title'));
  }
  $this.focus(function() {
    if($this.val() === $this.attr('title')) {
      $this.val('');
    }
  });
  $this.blur(function() {
    if($this.val() === '') {
      $this.val($this.attr('title'));
    }
  });
});
	 
	 
	 
      
    });
     
     
     
    </script>
<style>
.hide, .profile_blk {
	display:none;
}
</style>
<div class="page_tit">Join Free </div>
<div class="body_part_inner">
<div class="pls_choose_tit">Please Choose</div>
<div class="weare_employer_tit" onclick="select_type('.company')" style="cursor:pointer;">We are Company / Employer</div>
<div class="join_jobseeker_tit job_seeker join_jobseeker_tit2 job_seeker"  style="cursor:pointer;"  onclick="select_type('.job_seeker')">I am a Job Seeker</div>
<table width="900" border="0">
  <tr valign="top">
    <td style="width: 523px;" width="523"><div class="profile_blk" >
        <div class="role1"> <span class="role job_seeker hide">New job seeker registration </span> <span class="role company hide">New company registration </span> </div>
        <div class="pwd_blk">
          <div class="reg_form_box_inside">
            <div class="newpwd"> Username </div>
            <input  type="text" id="new_user_username"  class="pwdbg2" />
            <div class="newpwd company hide"> Company Name </div>
            <div class="newpwd job_seeker hide"> First Name </div>
            <input  type="text" class="pwdbg2 company job_seeker hide" />
            <div class="newpwd job_seeker hide"> Last Name </div>
            <input name="text2"  type="text"  class="pwdbg2 job_seeker hide" />
            <div class="newpwd"> Your E-mail </div>
            <input  type="text" id="new_user_email"  class="pwdbg2" />
            <div class="newpwd"> Set Password </div>
            <input  type="password" id="new_user_password" class="pwdbg2" />
            <div class="newpwd"> Repeat your Password </div>
            <input  type="password" id="new_user_password2" class="pwdbg2" />
            <div class="newpwd job_seeker hide"> Select Role </div>
            <select id="new_user_role" class="profile_drop job_seeker hide" name="role" id="" style="margin-bottom:30px">
            <option>Allied Professional / Physician</option>
            <option class="company hide" value="company">company</option>
            </select>
            <div class="newpwd"> </div>
            <label for="i_agree">
              <input type="checkbox" name="i_agree" id="i_agree" value="yes" />
              I agree with the the <a href="<? print site_url('terms-and-conditions') ?>" class="blue" target="_blank">Terms & Conditions</a> </label>
            <div style="display:block; width:100%; clear:both; height:50px;">
              <div class="join_chkbox"> </div>
              <div class="join_chkbox_desc"></div>
            </div>
            <div style="display:block; width:100%; clear:both; height:50px;">
              <div class="newpwd"> Captcha <br />
                <small><a href="javascript:document.getElementById('captcha').src='<? print site_url('captcha')?>/'+Math.random(); void(0)" class="blue">(reload)</a></small> </div>
              <a href="javascript:document.getElementById('captcha').src='<? print site_url('captcha')?>/'+Math.random(); void(0)"
    id="change-image"  style="float:right;"> <img src="<? print site_url('captcha')?>" id="captcha" /> </a> </div>
            <div class="newpwd"> </div>
            <input type="text"   title="Enter the text from the image"  class="pwdbg2" id="new_user_captcha"  />
            <div class="join_create_acc_but">
              <input type="image" onclick="register();"  src="<? print TEMPLATE_URL ?>images/join_create_acc_but.jpg" />
            </div>
          </div>
          <div class="reg_form_box_inside2" style="display:none;">
            <h2>Processing, please wait...</h2>
          </div>
        </div>
      </div>
  </div>
  </td>
  
  <!-- <td style="width: 323px;" width="323">
    
    <div class="richtext company job_seeker hide" style="color:#666">
    <h4>HTML Ipsum Presents</h4>
	       
<p><strong>Pellentesque habitant morbi tristique</strong> senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper.    elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. <a href="#">Donec non enim</a> in turpis pulvinar facilisis. Ut felis.</p>
<br />

    <ul>
   <li>Morbi in sem quis dui placerat ornare. Pellentesque odio nisi, euismod in, pharetra a, ultricies in, diam. Sed arcu. Cras consequat.</li>
   <li>Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.</li>
    
 </ul>
            
    </div>
    </td>-->
  </tr>
  
</table>
</div>

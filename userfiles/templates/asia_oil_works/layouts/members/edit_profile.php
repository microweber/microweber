<!-- Secondary navigation -->
<? $form_values = get_user(); 

//p( $form_values);
?>
<script>
 
 function process_account_save(msg){
	// alert(msg);
	  
	 if(msg.error){
	 $errors = 'Error' + '\n';
		
			var err = msg.error;
			
			$.each(err, function(key, value) {
			$errors = $errors+ '\n' + value;
			});
			
			alert($errors);
			
	
	 } else {
		 //window.location.reload()
		 
		 
	 }
	  if(msg.success){
		 new Notification('<strong>You</strong> just saved <strong>your profile</strong>', 'saved');
		  
		  
	  }
	 
	 
 }
						
						
		
 
 </script>
<script type="text/javascript">

	function ajaxFileUpload_toggle(){
		
		
		$("#user_pic_holder_uploaders").fadeIn();
		$("#user_pic_holder_uploaders_btn_1").fadeOut();
		
		
	}


	function ajaxFileUpload()
	{
		 
		$.ajaxFileUpload
		(
			{
				url:'<? print site_url('api/media/upload'); ?>',
				secureuri:false,
				fileElementId:'fileToUpload',
				dataType: 'json',
				data:{for:'table_users', id:'<? print user_id(); ?>'},
				success: function (data, status)
				{
					
					
					
					if(typeof(data.done) != 'undefined')
					{
						 
								
								$("#user_pic_holder").html('<img src="'+data.url+'" />');
								$("#user_pic_uploaded").val(data.url);
					}
					
						 
				} 
			}
		)
		
		return false;

	}
    
    
    function cv_ajaxFileUpload_toggle(){
		
		
		$("#cv_user_pic_holder_uploaders").fadeIn();
		$("#cv_user_pic_holder_uploaders_btn_1").fadeOut();
		
		
	}


	function  cv_ajaxFileUpload()
	{
		 
		$.ajaxFileUpload
		(
			{
				url:'<? print site_url('api/media/upload'); ?>',
				secureuri:false,
				fileElementId:'cv_fileToUpload',
				dataType: 'json',
				data:{for:'table_users', id:'<? print user_id(); ?>'},
				success: function (data, status)
				{
					
					
					
					if(typeof(data.done) != 'undefined')
					{
						 
									$("#cv_user_pic_holder").html('<a href="'+data.url+'" target="_blank">View the new CV</a>');
							//	$("#cv_user_pic_holder").html(data.url);
								$("#cv_user_pic_uploaded").val(data.url);
					}
					
						 
				} 
			}
		)
		
		return false;

	}
    
    
    
	
	
	
    
    </script>

<nav id="secondary">
  <ul>
    <li class="current"><a href="#maintab">Personal info</a></li>
    <li><a href="#secondtab">Username and password</a></li>
  </ul>
</nav>
<!-- The content -->
<section id="content_tabs">
  <div class="tab" id="maintab">
    <h2>Account details</h2>
    <form class="wymupdate" id="profile_data" onsubmit="mw.users.save('#profile_data', process_account_save); return false;">
      <div>
        <section>
          <label> Account type <small>- as <strong>"Job seeker"</strong> you can post your CV on jobs</small> <small>- as <strong>"Company"</strong> you can post jobs for the Job seekers</small> </label>
          <div>
            <select name="role">
              <option value="job_seeker" <? if( $form_values['role'] == 'job_seeker'):  ?> selected="selected"   <? endif; ?>  >Job seeker</option>
              <option value="company" <? if( $form_values['role'] == 'company'):  ?> selected="selected"   <? endif; ?>   >Company</option>
            </select>
          </div>
        </section>
      </div>
      <div class="clear"></div>
      <div>
        <section>
          <label>Profile privacy</label>
          <div>
            <select name="is_public">
              <option value="y" <? if( $form_values['role'] == 'y'):  ?> selected="selected"   <? endif; ?>  >Public - shows in searches and lists</option>
              <option value="n" <? if( $form_values['role'] == 'n'):  ?> selected="selected"   <? endif; ?>   >Private - hide from searches and lists</option>
            </select>
          </div>
        </section>
      </div>
      <div class="clear"></div>
      <div class="clear"></div>
      <br>
      <div>
        <section>
          <label for="address"> Names </label>
          <div> Please enter your <em>real</em> names.<br />
            <br />
            <input name="custom_field_first_name" id="address"   value="<? print $form_values['custom_fields']['first_name'];  ?>" type="text" class="small" />
            <input   type="text" class="small" name="custom_field_last_name" value="<? print $form_values['custom_fields']['last_name'];  ?>" />
          </div>
        </section>
        <section>
          <label for="username"> Email* <small>Your email address must be valid</small> </label>
          <div>
            <input type="text" id="email" name="email"   class="required"  value="<? print $form_values['email'];  ?>" />
          </div>
        </section>
        <section>
          <label for="address"> Country </label>
          <div> Please enter your <em>current</em> country.<br />
            <br />
            <textarea class="required" id="textarea" name="custom_field_country"><? print $form_values['custom_fields']['country'];  ?></textarea>
          </div>
        </section>
        <section>
          <label for="address"> Address </label>
          <div> Please enter your <em>current</em> address.<br />
            <br />
            <textarea class="required" id="textarea" name="custom_field_address"><? print $form_values['custom_fields']['address'];  ?></textarea>
          </div>
        </section>
        <section>
          <label for="address"> Phones </label>
          <div> Please enter your <em>current</em> phones.<br />
            <br />
            <input name="custom_field_phones" id="phones"   value="<? print $form_values['custom_fields']['phones'];  ?>" type="text"  />
          </div>
        </section>
        <section>
          <label for=" "> Picture </label>
          <div> Please upload a picture of yourself.<br />
            <br />
            <div id="user_pic_holder">
              <? if (trim($form_values['custom_fields']['picture']) != '' ):?>
              <img src="<? print $form_values['custom_fields']['picture'];  ?>" />
              <? endif; ?>
              <button class="button" id="user_pic_holder_uploaders_btn_1" onclick="ajaxFileUpload_toggle();">Change picture</button>
            </div>
            <div id="user_pic_holder_uploaders" <? if (trim($form_values['custom_fields']['picture']) != '' ):?> style="display:none"<? endif; ?>>
              <input id="fileToUpload" type="file"   name="fileToUpload" class="input" onchange="ajaxFileUpload();" onblur="ajaxFileUpload();">
              <button class="button" id="buttonUpload" onclick="return ajaxFileUpload();">Upload</button>
            </div>
            <input name="custom_field_picture"  id="user_pic_uploaded" value="<? print $form_values['custom_fields']['picture'];  ?>" type="hidden"  />
          </div>
        </section>
        <section>
          <label for=" "> CV </label>
          <div> Please upload your CV<br />
            <br />
            <div id="cv_user_pic_holder">
              <? if (trim($form_values['custom_fields']['cv']) != '' ):?>
              <? $fn = ( pathinfo_utf($form_values['custom_fields']['cv']));  ?>
              <a href=" <? print $form_values['custom_fields']['cv'];  ?>" target="_blank"><? print $fn['filename'] ?>.<? print $fn['extension'] ?></a>
              <button class="button" id="cv_user_pic_holder_uploaders_btn_1" onclick="cv_ajaxFileUpload_toggle();">Change cv</button>
              <? endif; ?>
            </div>
            <div id="cv_user_pic_holder_uploaders" <? if (trim($form_values['custom_fields']['cv']) != '' ):?> style="display:none"<? endif; ?>>
              <input id="cv_fileToUpload" type="file"   name="cv_fileToUpload" class="input" onchange="cv_ajaxFileUpload();" onblur="cv_ajaxFileUpload();">
              <button class="button" id="cv_buttonUpload" onclick="return cv_ajaxFileUpload();">Upload</button>
            </div>
            <input name="custom_field_cv"  id="cv_user_pic_uploaded" value="<? print $form_values['custom_fields']['cv'];  ?>" type="hidden"  />
          </div>
        </section>
      </div>
      <div class="clear"></div>
      <h2>Enter something about yourself (free text)</h2>
      <textarea    name="custom_field_info"><? print $form_values['custom_fields']['info'];  ?></textarea>
      <br />
      <p>
        <input type="submit" class="button primary submit" value="Submit" />
      </p>
    </form>
    <div class="clear"></div>
  </div>
  <div class="tab" id="secondtab">
    <h2>Account details</h2>
    <form class="wymupdate" id="profile_pass" onsubmit="mw.users.save('#profile_pass', process_account_save); return false;">
      <div>
        <section>
          <label for="username"> Username* <small>The username must consist of at least 3 characters</small> </label>
          <div>
            <input type="text" id="username" name="username"   class="required" minlength="3" value="<? print $form_values['username'];  ?>" />
            <a href="#" class="button icon loop">Check availability</a> </div>
        </section>
        <section>
          <label for="password"> Password* <small>The password must consist of at least 6 characters</small> </label>
          <div>
            <input placeholder="At least 8 characters" name="password" id="password" type="password" class="required" minlength="6" value="<? print $form_values['password'];  ?>" />
            <input name="confirm_password" type="password" placeholder="Confirm password" value="<? print $form_values['password'];  ?>" />
          </div>
        </section>
      </div>
      <div class="clear"></div>
      <br />
      <p>
        <input type="submit" class="button primary submit" value="Save changes" />
      </p>
    </form>
    <div class="clear"></div>
  </div>
</section>

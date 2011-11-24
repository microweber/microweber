<div class="border-bottom" id="top-inner-title" style="margin-bottom: 7px; padding:6px 10px 6px 0;">
    <h2 style="">Change password</h2>
  </div>
<script type="text/javascript">
// prepare the form when the DOM is ready 
$(document).ready(function() { 
    var options = { 
        //target:        '#output1',   // target element(s) to be updated with server response 
		url:       '<?php print site_url('users/user_action:password') ?>',  
		type: 'post',
		
        beforeSubmit:  change_pass_showRequest,  // pre-submit callback 
        success:       change_pass_showResponse  // post-submit callback 
 
 
 
        // other available options: 
        //url:       url         // override for form's 'action' attribute 
        //type:      type        // 'get' or 'post', override for form's 'method' attribute 
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 
 
        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    }; 
 
    // bind form using 'ajaxForm' 
    $('#change_password_form').ajaxForm(options); 
}); 
 
// pre-submit callback 
function change_pass_showRequest(formData, jqForm, options) { 
     
    return true; 
} 
 
// post-submit callback 
function  change_pass_showResponse(responseText, statusText, xhr, $form)  { 


if(responseText == 'ok'){
	$('#change_password_form_output').html('');
	$('#change_password_form_saved').show();

} else {
	$('#change_password_form_output').html(responseText);
	$('#change_password_form_saved').hide();
}
    
	
	
} 
</script>

<div id="change_password_form_output"></div>


<div id="change_password_form_saved" style="display:none">Great! Password is chaged!</div>


<form action="" id="change_password_form" method="post" class="form">



<label class="rnblabel">Old Password</label>

 <span class="linput"><input style="width: 265px;" name="old_password" type="password" /></span>

<label class="rnblabel">New Password</label>

<span class="linput"><input style="width: 265px;" name="new_password" type="password" /></span>


<label class="rnblabel">Confirm Password</label>

<span class="linput"><input style="width: 265px;" name="new_password2" type="password" /></span>

<input type="submit" value="" class="hidden" />
<div class="c" style="padding-bottom: 15px;">&nbsp;</div>

<a href="javascript:void(0);" onclick="$(this).parent().submit();" class="btn"><span class="btn-right"><samp class="btn-mid">Change Pass</samp></span></a>


</form>















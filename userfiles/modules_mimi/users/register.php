<?

/**
 * 
 * 
 * Generic module to display posts list.
 * @author Peter Ivanov
 * @package content


Example:
 @example:  

 <microweber module="users/register" parent_id="<? print user_id(); ?>" title="Add kid" no_email="true"></microweber>
 


 //params for the data
 @param $parent_id | adds the user as subuser  | default:false
 @param $title | the title of the form | default:false
 @param $no_email | if true will remove the email field | default:false
 @param $redirect_on_success | where do you want theu ser to be redirected, if false the user stays on this page | default: false
 
 
 

 */

?>
<?

//include_once(APPPATH.'libraries/recaptcha.php');
//$capcha = new Recaptcha();

?>
<script type="text/javascript">
            $(document).ready(function(){

$("#register_step_2_anchor").click(function(){

});
$("#register_step_2_back").click(function(){



});


$("input[name='username']").keyup(function(){
   this.value = this.value.replace(/\s/g, "");
});


window.onhash("#step-1", function(){
        $("#register_step_1").show();
       $("#register_step_2").hide();
});
window.onhash("", function(){
        $("#register_step_1").show();
       $("#register_step_2").hide();
});
window.onhash("#step-2", function(){
   if($("#register_step_1").isValid()){
      if($("input[name='custom_field_gender']").val() != '' && $("input[name='custom_field_gender']:checked").val() != undefined){
         $("#register_step_1").hide();
         $("#register_step_2").show();
      }
   }
   else{
     window.location.hash='#step-1'
   }
});



$("#register").validate(function(){

var data = $(this).dataCollect();
$.post("<? print site_url('api/user/register') ?>", data, function(resp){

var resp_msg = '';

if(isobj(resp.error) != false){
jQuery.each(resp.error, function(i, val) {
      //$("#" + i).append(document.createTextNode(" - " + val));
	  resp_msg = resp_msg + '<br />' + val;
    });	

mw.box.alert(resp_msg);
	}



if(isobj(resp.success) != false){

<? if($auto_login == false): ?>
<? if($redirect_on_success == false): ?>
$("#success").fadeIn();
$("#register").fadeOut();
<? else: ?>
  window.location.href='<? print $redirect_on_success; ?>'+'/user_id:'+resp.success.id;
<? endif; ?> 
<? endif; ?> 

<? if($auto_login == true): ?>
//

$.post("<? print site_url('api/user') ?>", resp.success, function(resp1){
																   
		//alert(resp1); 	
		
		if(isobj(resp1.ok) != false){
			<? if($redirect_on_success != false): ?>
			  window.location.href='<? print $redirect_on_success; ?>'+'/user_id:'+resp.success.id;
			<? else: ?>
			 window.location.href='<? print site_url('dashboard') ?>'
			<? endif; ?> 
		}
		
}, "json");

<? endif; ?> 

 
}








}, "json");

});



var gender_val = $("#reg_genders input:checked").val();

if(gender_val=='male'){
  $(".reg_gender_male a").addClass("active")
}
else if(gender_val=='female'){
 $(".reg_gender_female a").addClass("active")
}


            });


         </script>
<style>
#register_step_2 .login-item {
	width: 315px;
}
.form .login-item input {
	width: 300px;
}
</style>
<? if($title) : ?>
<h2><? print $title; ?></h2>
<? else: ?>
<h2>Register</h2>
<?php endif ?>
<br />
<form  method="post" id="register" class="form">
  <?php if(!empty($user_register_errors)) : ?>
  <ol class="submit-error-info">
    <?php foreach($user_register_errors as $k => $v) :  ?>
    <li><?php print $v ?></li>
    <?php endforeach; ?>
  </ol>
  <?php endif ?>
  <? if($parent_id): ?>
  <input name="parent_id" type="hidden" value="<? print $parent_id; ?>" />
  <?php endif ?>
   <input name="is_active" type="hidden" value="y" />
  
  <div id="register_step_1">
    <div class="bluebox">
      <div class="blueboxcontent">
        <h2>Birthdate &amp; Gender</h2>
        <br />
        <div class="hr"></div>
        <div class="ghr"></div>
        <div align="center">
          <label style="padding-bottom: 7px;">Please enter a <strong>valid</strong> birthdate</label>
          <span>
          <select class="required" style="width: 120px;" name="custom_field_bmonth">
            <option value="">Month</option>
            <? for ($i = 1; $i <= 12; $i++) :?>
            <option  <? if($form_values['custom_field_bmonth'] == $i): ?> selected="selected" <? endif; ?>   value="<? print $i ?>"><? print  date("F", mktime(0, 0, 0, $i, 1, 2000)); ?>&nbsp;</option>
            <? endfor; ?>
          </select>
          </span> <span>
          <select class="required" style="width: 100px;" name="custom_field_bday">
            <option value="">Day&nbsp;</option>
            <? for ($i = 1; $i <= 31; $i++) :?>
            <option <? if($form_values['custom_field_bday'] == $i): ?> selected="selected" <? endif; ?> value="<? print $i ?>"><? print $i ?>&nbsp;</option>
            <? endfor; ?>
          </select>
          </span> <span>
          <select class="required" style="width: 80px;" name="custom_field_byear">
            <option value="">Year&nbsp;</option>
            <? for ($i = date("Y"); $i >= 1950; $i--) :?>
            <option <? if($form_values['custom_field_byear'] == $i): ?> selected="selected" <? endif; ?> value="<? print $i ?>"><? print $i ?>&nbsp;</option>
            <? endfor; ?>
          </select>
          </span> </div>
        <div class="c">&nbsp;</div>
        <br />
        <div class="ghr" style="width:400px"></div>
        <div align="center">
          <div id="reg_genders">
            <div class="reg_gender_male" onclick="$('#gender_male').check();$('.reg_gender_female a').removeClass('active');$(this).find('a').addClass('active')"> <a href="#"><span></span><strong>Male</strong></a> </div>
            <div class="reg_gender_female" onclick="$('#gender_female').check();$('.reg_gender_male a').removeClass('active');$(this).find('a').addClass('active')"> <a href="#"><span></span><strong>Female</strong></a> </div>
            <input class="xhidden" name="custom_field_gender" type="radio" <? if($form_values['custom_field_gender'] == 'male'): ?> checked="checked" <? endif; ?>  value="male" id="gender_male" />
            <input class="xhidden" name="custom_field_gender" type="radio" value="female" <? if($form_values['custom_field_gender'] == 'female'): ?> checked="checked" <? endif; ?> id="gender_female" />
          </div>
          <div class="c">&nbsp;</div>
          <br />
        </div>
      </div>
    </div>
    <br />
    <a class="mw_btn_x right" href="#step-2" id="register_step_2_anchor"><span>Proceed</span></a>
    <div class="c">&nbsp;</div>
    <br />
  </div>
  <div id="register_step_2">
    <div class="bluebox">
      <div class="blueboxcontent">
        <h2>Account details</h2>
        <br />
        <div class="hr"></div>
        <div class="ghr"></div>
        <label style="text-align: center">You'll need an <b>username</b> and <b>password</b> to log in </label>
        <div class="login-item">
          <label>Username: <strong>*</strong></label>
          <span class="linput">
          <input tabindex="1"  class="required" name="username" type="text" value="<?php print $form_values['username'];  ?>">
          </span> </div>
        <? if($no_email == false) : ?>
        <div class="login-item">
          <label>Email: <strong>*</strong></label>
          <span class="linput">
          <input tabindex="2" class="required-email" name="email" type="text" value="<?php print $form_values['email'];  ?>">
          </span> </div>
        <?php endif ?>
        <div class="login-item">
          <label>Password: <strong>*</strong></label>
          <span class="linput">
          <input tabindex="3" class="required-equal" equalto="this" name="password" type="password" value="<?php print $form_values['password'];  ?>">
          </span> </div>
        <div class="login-item">
          <label>Retype Password: <strong>*</strong></label>
          <span class="linput">
          <input tabindex="4" class="required-equal" equalto="this" name="password2" type="password" value="<?php print $form_values['password2'];  ?>">
          </span> </div>
        <br />
        <br />
      </div>
    </div>
    <div class="c" style="padding-bottom: 20px;">&nbsp;</div>
    <a class="mw_btn_x left" href="#step-1" id="register_step_2_back"><span>Back</span></a> <a class="mw_btn_x right submit" href="#" id="regth"><span>Register</span></a>
    <div class="c" style="padding-bottom: 20px;">&nbsp;</div>
  </div>
</form>
<div class="bluebox" id="success" style="display:none">
  <div class="blueboxcontent">
    <style type="text/css">



               </style>
    <h1 class="content-title"  style="padding-bottom:10px">Registration Successful</h1>
    <div class="hr"></div>
    <div class="ghr"></div>
    <div class="c" style="padding-bottom:15px;"></div>
    <div class="pad">
      <p><strong>Thank you for registering.</strong> </p>
      <br />
      <p> <a style="font:bold 12px Arial;color:#555;border-bottom: 1px dotted #555;" href="<?php print site_url('users/user_action:login'); ?>">To log in you have to enter your username and password.</a></p>
    </div>
  </div>
</div>

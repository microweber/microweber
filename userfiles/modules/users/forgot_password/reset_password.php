<div class="mw-o-box">

<?php $form_btn_title =  get_option('form_btn_title', $params['id']);
		if($form_btn_title == false) { 
		$form_btn_title = 'Save new password';
		}

 		 ?>
<? //$rand = uniqid(); ?>
<script  type="text/javascript">

mw.require('forms.js', true);


$(document).ready(function(){
	 mw.$('#user_reset_password_form<? print $params['id'];  ?>').submit(function() {
 		mw.form.post(mw.$('#user_reset_password_form<? print $params['id'];  ?>') , '<? print site_url('api') ?>/user_reset_password_from_link', function(){
	    var is_new_pass =  mw.response('#user_reset_password_form<? print $params['id'];  ?>',this);
		
 		if(is_new_pass == true){

			$('.reset-pass-form-wrap').hide();
		    $('.reset-pass-form-wrap-success').show();
			
		}
		

	// mw.reload_module('[data-type="categories"]');
	 // mw.reload_module('[data-type="pages"]');
	 });

 return false;
 
 
 });
 
});
</script>

<div class="box-head mw-o-box-header">
  <h2>Reset your password</h2>
</div>
<div class="mw-o-box-content" id="form-holder<? print $params['id'];  ?>">
  <? if(isset($_GET['reset_password_link']) == true): ?>
  <?
$reset = db_escape_string($_GET['reset_password_link']);
$data = get_users("single=true&password_reset_hash=".$reset); ?>
  <? if(isarr($data)): ?>
  <form id="user_reset_password_form<? print $params['id'];  ?>" method="post" class="clearfix">
    <div class="reset-pass-form-wrap">

      <input type="hidden" name="password_reset_hash" value="<? print $reset; ?>" />
      <input type="hidden" name="id" value="<? print $data['id']; ?>" />
      <!--<div class="control-group">
      <label class="control-label">Choose Username</label>
      <div class="controls">
        <input type="text" placeholder="Choose your username" name="username" value="<? print $data['username']; ?>">
      </div>
    </div>-->
      <div class="control-group mw-ui-field-holder">
       <!-- <label class="control-label">Enter new password</label>-->
        <div class="controls">
          <input type="password" class="mw-ui-field field-full" placeholder="Choose a password" name="pass1" >
        </div>
      </div>
      <div class="control-group mw-ui-field-holder">
<!--        <label class="control-label">Repeat new password</label>
-->        <div class="controls">
          <input type="password" class="mw-ui-field field-full"  placeholder="Repeat the password" name="pass2" >
        </div>
      </div>






      <div class="control-group">
        <div class="controls mw-ui-field-holder">
          <div class="input-prepend mw-ui-field mw-ico-field">
            <span style="width: 100px;background: white" class="add-on left">
                <img class="mw-captcha-img" src="<? print site_url('api/captcha') ?>" onclick="mw.tools.refresh_image(this);" />
            </span>
            <input type="text" placeholder="Enter the text" class="mw-ui-invisible-field mw-captcha-input" name="captcha">
          </div>
        </div>
      </div>





     <div class="vSpace"></div>



      <a class="btn btn-large pull-left mw-ui-btn" href="<? print curent_url(true,true); ?>">Back</a>
      <button type="submit" class="btn btn-large pull-right btn-success mw-ui-btn mw-ui-btn-green"><? print $form_btn_title ?></button>
    </div>
    <div style="clear: both"></div>
  </form>
  <div class="alert" style="margin-top: 20px;display: none;"></div>
  <? else : ?>
  <div class="alert alert-warining text-center">Invalid or expired link. 
  <br /><br />
<a class="btn  btn-info" href="<? print curent_url(true,true); ?>">Go back</a>  
  </div>
  <? endif; ?>
  <? else : ?>
  <div class="alert alert-warining text-center">You must click on the password reset link sent on your email.<br /><br />
<a class="btn  btn-info" href="<? print curent_url(true,true); ?>">Go back</a>  </div>
  <? endif; ?>
  <div class="reset-pass-form-wrap-success" style="display:none"> <a class="btn  btn-primary" href="<? print curent_url(true,true); ?>">Click here to login with the new password</a> </div>
</div>

</div>

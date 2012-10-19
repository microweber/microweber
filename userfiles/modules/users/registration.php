<? $rand = uniqid(); ?>
<script  type="text/javascript">

mw.require('forms.js');
 

$(document).ready(function(){
	
	 
	 
	 mw.$('#user_registration_form<? print $rand ?>').submit(function() { 

 
 mw.form.post(mw.$('#user_registration_form<? print $rand ?>') , '<? print site_url('api') ?>/register_user', function(){
	 
	 
	// mw.reload_module('[data-type="categories"]');
	 // mw.reload_module('[data-type="pages_menu"]');
	 });

 return false;
 
 
 });
   
 


 
   
});
</script>

<form class="form-horizontal" id="user_registration_form<? print $rand ?>" method="post">
  <legend><?php print option_get('form_title', $params['id']) ?></legend>
  <div class="control-group">
    <label class="control-label" for="email">Email</label>
    <div class="controls">
      <input type="text"   name="email" placeholder="Email">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="password">Password</label>
    <div class="controls">
      <input type="password"   name="password" placeholder="Password">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="captcha" >Captcha</label>
    <div class="controls"> <img src="<? print site_url('api/captcha') ?>" onclick="this.src='<? print site_url('api/captcha') ?>'" />
      <input type="captcha"   name="captcha" placeholder="?">
    </div>
  </div>
  <div class="control-group">
    <div class="controls"> 
      <!-- <label class="checkbox">
        <input type="checkbox">
        Remember me </label>-->
      <?php $form_btn_title =  option_get('form_btn_title', $params['id']);
		if($form_btn_title == false) { 
		$form_btn_title = 'Register';
		}
		 ?>
      <button type="submit" class="btn"><? print $form_btn_title ?></button>
    </div>
  </div>
</form>

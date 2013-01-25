<?php  $form_id = "mw_contact_form_". rand();  ?>
<? $save_as = get_option('form_name', $params['id']);

if($save_as == false){
$save_as = $params['id'];
}
 ?>
<script  type="text/javascript">
  mw.require("forms.js");
</script>

<script  type="text/javascript">
$(document).ready(function(){

  $('form[data-form-id="<? print $form_id ?>"]').submit(function() {
 var append_to_form = '<input type="hidden" name="module_name" value="<? print $params['module'] ?>" />';
 	$(this).append(append_to_form); 
	
     mw.form.post('form[data-form-id="<? print $form_id ?>"]');
    return false;
  });
});
</script>

<div class="edit nodrop" data-field="form_title" rel="module" data-id="<? print $params['id'] ?>">
  <h1>My form title</h1>
</div>
<div class="edit nodrop" data-field="form_desc" rel="module" data-id="<? print $params['id'] ?>">
  <p>My form description</p>
</div>
<form class="mw_form" data-form-id="<? print $form_id ?>" name="<? print $form_id ?>" method="post" >
  <module type="custom_fields" data-id="<? print $params['id'] ?>" data-for="module"   />
  <? if(get_option('disable_captcha', $params['id']) !='y'): ?>
  <div class="mw-custom-field-group">
    <label class="mw-custom-field-label">Security code</label>
    <div class="mw-custom-field-form-controls"> <img  class="mw-captcha-img" src="<? print api_url('captcha') ?>" />
      <input name="captcha" type="text"  class="mw-captcha-input"/>
    </div>
  </div>
  <?  endif;?>
  <input type="submit"  value="submit" />
</form>

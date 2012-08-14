<?php $form_title = option_get('form_title', $params['id']);
 $temp_id = "mw_contact_form_".url_title($form_title) .'_'. rand(); 
 
 if($form_title == '' or $form_title == false){
	$form_title = "My form title"; 
 }

 ?>
<script  type="text/javascript">
$(document).ready(function(){
				
			 
		mw.require("forms.js", function(){


$('form[data-temp-id="<? print $temp_id ?>"]').submit(function() {
  //alert('Handler for .submit() called.');+
  mw.form.post('form[data-temp-id="<? print $temp_id ?>"]')
  return false;
});






			//alert(mw.form);
			//onsubmit="mw.form.post(this)"



			});
		 
			});

</script>

<h1><? print $form_title; ?></h1>
<form class="mw_form" data-temp-id="<? print $temp_id ?>" >
  <? $save_as = option_get('form_save_as', $params['id']);

if($save_as == false){
$save_as = option_get('form_title', $params['id']);
}
 ?>
  <input  type="hidden" name="form_title" value="<? print $save_as; ?>" />
  <module type="custom_fields" id="<? print $params['id'] ?>"   />
  <input type="submit"  value="submit" />
</form>

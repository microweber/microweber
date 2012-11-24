<?php $form_title = option_get('form_title', $params['id']);
 
  $temp_id = "mw_contact_form_".url_title($form_title) .'_'. rand(); 
 
 if($form_title == '' or $form_title == false){
	$form_title = "My form title"; 
 }

 ?>
<script  type="text/javascript">


// mw.require("forms.js");

$(document).ready(function(){

  mw.$('form[data-temp-id="<? print $temp_id ?>"]').submit(function() {
  //  mw.form.post('form[data-temp-id="<? print $temp_id ?>"]')
   // return false;
  });
});
</script>

<div class="edit" field="form_title"  rel="module" data-option_group="<? print $params['id'] ?>" data-module="<? print $params['type'] ?>">
  <h1>This is My editable title</h1>
</div>

<div class="edit" field="form_sub_title"  rel="module" data-option_group="<? print $params['id'] ?>" data-module="<? print $params['type'] ?>">
  My form decription
</div>

<div class="edit" field="data-address"  rel="module" data-option_group="<? print $params['id'] ?>" data-module="<? print $params['type'] ?>">
My address
</div>
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

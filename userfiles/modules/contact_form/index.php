<?php  $form_id = "mw_contact_form_".$params['id'];  ?>
<script  type="text/javascript">
	mw.require("forms.js", true);
</script>

<script  type="text/javascript">
$(document).ready(function(){

	$('form[data-form-id="<? print $form_id ?>"]').submit(function() {
		var this_form = this;
		var append_to_form = '<input type="hidden" name="module_name" value="<? print $params['module'] ?>" />';
		$(this).append(append_to_form);
		mw.form.post('form[data-form-id="<? print $form_id ?>"]', undefined, function(form){
			var data2 =  (this);
			if(typeof(data2.error) != 'undefined'){
			mw.response(mw.$(this_form),data2);
		} else {
			
			$(form).addClass("deactivated");
	        mw.$("#msg<? print $form_id ?>").css("top", "20%");
	        if($(form).find(".mw-captcha-img")[0].length > 0){
		        mw.tools.refresh_image($(form).find(".mw-captcha-img")[0]);
	        }
	        form.reset();
			$(this_form).find(".alert-error ").remove();
			$(form).find(".alert-error ").remove();
	        setTimeout(function(){
	        mw.$("#msg<? print $form_id ?>").css("top", "-100%");
	        $(form).removeClass("deactivated");
	         
	        }, 3200);

            }
     	});
    	return false;
    });
});
</script>
<? $save_as = get_option('form_name', $params['id']);

if($save_as == false){
	$save_as = $params['id'];
}
 
$module_template = get_option('data-template', $params['id']);

if($module_template != false and $module_template != 'none'){
	$template_file = module_templates( $config['module'], $module_template);
} else {
	if(isset($params['template'])){
			$template_file = module_templates( $config['module'], $params['template']);

	} else {
			$template_file = module_templates( $config['module'], 'default');

	}
	
}

if(isset($template_file) and is_file($template_file) != false){
 	include($template_file);
} else {
	print 'No template for contact form is found';
}
?>
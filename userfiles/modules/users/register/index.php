<div class="mw-user-reg-holder">

	<?php $form_btn_title =  get_option('form_btn_title', $params['id']);
		if($form_btn_title == false) {
		$form_btn_title = 'Register';
		}


$enable_user_fb_registration =  get_option('enable_user_fb_registration', $params['id']);
if($enable_user_fb_registration == 'y') {
$enable_user_fb_registration = true;
} else {
$enable_user_fb_registration = false;
}

if($enable_user_fb_registration == true){
	$enable_user_fb_registration_site =  get_option('enable_user_fb_registration', 'users');
	if($enable_user_fb_registration_site == 'y') {
	$enable_user_fb_registration = true;

	$fb_app_id  = get_option('fb_app_id','users');
	$fb_app_secret  = get_option('fb_app_secret','users');

	if($fb_app_id != false){
	$fb_app_id = trim($fb_app_id);
	}

	if($fb_app_secret != false){
	$fb_app_secret = trim($fb_app_secret);
	}



	if($fb_app_id == ''){
	$enable_user_fb_registration = false;
	}

	} else {
	$enable_user_fb_registration = false;

	}
}

$form_show_first_name = get_option('form_show_first_name','users')=='y';

$form_show_last_name = get_option('form_show_last_name','users')=='y';;

$form_show_password_confirmation = get_option('form_show_password_confirmation','users')=='y';;

$form_show_address = get_option('form_show_address','users')=='y';;





$module_template = get_option('data-template',$params['id']);
if($module_template == false and isset($params['template'])){
	$module_template =$params['template'];
}





if($module_template != false){
	$template_file = module_templates( $config['module'], $module_template);

} else {
	$template_file = module_templates( $config['module'], 'default');

}

if(isset($template_file) and ($template_file) != false and is_file($template_file) != false){
	include($template_file);
} else {
	$template_file = module_templates( $config['module'], 'default');
	if(($template_file) != false and is_file($template_file) != false){
		include($template_file);
	} else {
		$complete_fallback = dirname(__FILE__).DS.'templates'.DS.'default.php';
		 if(is_file($complete_fallback) != false){
			include($complete_fallback);
		}

	}
	//print 'No default template for '.  $config['module'] .' is found';
}

?>

</div>

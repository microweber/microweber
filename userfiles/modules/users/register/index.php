
<?php $user = user_id(); ?>
<?php $have_social_login = false; ?>
<?php if($user != false): ?>
<module type="users/profile"  />
<?php elseif(isset($_GET['reset_password_link'])): ?>
<module type="users/forgot_password" />
<?php else:  ?>
<?php $form_btn_title =  mw('option')->get('form_btn_title', $params['id']);
		if($form_btn_title == false) {
		$form_btn_title = 'Register';
		}


$enable_user_fb_registration =  mw('option')->get('enable_user_fb_registration', $params['id']);
if($enable_user_fb_registration == 'y') {
$enable_user_fb_registration = true;
} else {
$enable_user_fb_registration = false;
}

if($enable_user_fb_registration == true){
	$enable_user_fb_registration_site =  mw('option')->get('enable_user_fb_registration', 'users');
	if($enable_user_fb_registration_site == 'y') {
	$enable_user_fb_registration = true;

	$fb_app_id  = mw('option')->get('fb_app_id','users');
	$fb_app_secret  = mw('option')->get('fb_app_secret','users');

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



		 ?>
<?php //$rand = uniqid(); ?>
<?php
$module_template = mw('option')->get('data-template',$params['id']);
				if($module_template == false and isset($params['template'])){
					$module_template =$params['template'];
				}





				if($module_template != false){
						$template_file = module_templates( $config['module'], $module_template);

				} else {
						$template_file = module_templates( $config['module'], 'default');

				}

				//d($module_template );
				if(isset($template_file) and is_file($template_file) != false){
					include($template_file);
				} else {

						$template_file = module_templates( $config['module'], 'default');
				include($template_file);
					//print 'No default template for '.  $config['module'] .' is found';
				}

?>
<?php endif; ?>

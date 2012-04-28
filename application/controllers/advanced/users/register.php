<?php


$mw_user = $this->core_model->userId();
if(intval($mw_user) != 0){
	redirect ( 'dashboard' );
}

$user_action = 'register';

if ($_POST) {

	$this->template ['form_values'] = $_POST;

	$user_register_errors = array ();

	$username = $this->input->post ( 'username' );

	$email = $this->input->post ( 'email' );

	$password = $this->input->post ( 'password' );

	$password2 = $this->input->post ( 'password2' );

	$to_reg = array ();

	$to_reg ['username'] = $username;

	$to_reg ['email'] = $email;

	$to_reg ['password'] = $password;

	$to_reg ['is_active'] = 'n';

	$to_reg ['is_admin'] = 'n';

	//parrent  aff


	if (isset ( $_COOKIE ["microweber_referrer_user_id"] )) {
		$aff = intval ( $_COOKIE ["microweber_referrer_user_id"] );
		if ($aff != 0) {
			$to_reg ['parent_affil'] = $aff;
		}

	} else {

	}

	$reg_is_error = false;

	$check_if_exist = $this->users_model->checkUser ( 'username', $to_reg ['username'] );

	$check_if_exist_email = $this->users_model->checkUser ( 'email', $to_reg ['email'] );

	if ($username == '') {

		$reg_is_error = true;

		$user_register_errors ['username_not_here'] = 'Please enter username!';

	}

	if ($password == '') {

		$reg_is_error = true;

		$user_register_errors ['password_not_here'] = 'Please enter password!';

	}

	if ($email == '') {

		$reg_is_error = true;

		$user_register_errors ['email_not_here'] = 'Please enter email!';

	}

	if ($username != '') {

		if ($check_if_exist != 0) {

			$reg_is_error = true;

			$user_register_errors ['username_taken'] = 'This username is taken!';

		}

	}

	if ($email != '') {

		if ($check_if_exist_email != 0) {

			$reg_is_error = true;

			$user_register_errors ['username_emailtaken'] = 'User with this email already exists!';

		}

	}

	if ($password != '') {

		if ($password != $password2) {

			$reg_is_error = true;

			$user_register_errors ['passwords_dont_match'] = 'Password does not match!';

		}

	}

	if ($reg_is_error == true) {

		$this->template ['user_register_errors'] = $user_register_errors;

	} else {

		//Send mail
		//						$userdata = array ();
		//						$userdata ['id'] = $to_reg ['parent_affil'];
		//						$parent = $this->users_model->getUsers ( $userdata );
		//						//$this->dbQuery("select * from firecms_users where id={$to_reg ['parent_affil']}");
		//						$to_reg ['parent'] = $parent [0] ['username'];
		//
		//						$to_reg ['option_key'] = 'mail_new_user_reg';
		//						$this->core_model->sendMail ( $to_reg, true );


		//$primarycontent =$this->load->view ( 'me/register_done', true, true );
		$this->template ['user_registration_done'] = true;
		$userId = $this->users_model->saveUser ( $to_reg );

		/*~~~~~~~~~~~~~~~ Send activation email ~~~~~~~~~~~~~~~~~~~*/

		$emailTemplate = $this->core_model->optionsGetByKey ( 'registration_email', true );
		$from = $this->core_model->optionsGetByKey ( 'reg_email_from', true );

		$message = str_replace ( "{activation_url}", site_url ( 'users/user_action:activate/code:' . md5 ( $userId ) ), $emailTemplate ['option_value2'] );

		// Send activation email
		$sendOptions = array ('subject' => $emailTemplate ['option_value'], 'message' => $message, 'from_email' => $from ['option_value'], 'from_name' => $from ['option_value2'], 'to_email' => $to_reg ['email'] );

		$this->core_model->sendMail2 ( $sendOptions );

		/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

		// redirect user to back_url
		$back_to = CI::library('session')->userdata ( 'back_to' );
		if ($back_to) {

			$back_to = base64_decode ( $back_to );
			CI::library('session')->unset_userdata ( 'back_to' );

				if (trim ( $back_to ) != '') {

				header ( 'Location: ' . $back_to );
				exit ();
			}

		}

	}

	// $this->load->vars ( $this->template );

}

//$user_session ['user_action'] = $user_action;
// $this->load->vars ( $this->template );

$content ['content_filename'] = 'users/register.php';

?>
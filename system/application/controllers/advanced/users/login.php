<?php


$mw_user = CI::model('core')->userId();
if(intval($mw_user) != 0){
	redirect ( 'dashboard' );
}



$user_login_errors = array ();

if ($_POST) {

	$this->template ['form_values'] = $_POST;

	$username_or_email = $this->input->post ( 'username' );

	$password = $this->input->post ( 'password' );

	$check = array ();

	$check ['email'] = $username_or_email;

	$check ['password'] = $password;

	$check ['is_active'] = 'y';

	$check = CI::model('users')->getUsers ( $check );

	//	var_dump($check);
	if (empty ( $check [0] )) {

		$check = array ();

		$check ['username'] = $username_or_email;

		$check ['password'] = $password;

		$check ['is_active'] = 'y';

		$check = CI::model('users')->getUsers ( $check );

	}

	if (empty ( $check [0] )) {

		$user_login_errors ['login_failed'] = 'Login failed. Please verify your username and password.';

	} else {

        //sync with the forum userbase
        CI::model('users')->forum_sync($check[0]);

		$user_session ['is_logged'] = 'yes';

		$user_session ['user_id'] = $check [0] ['id'];

		CI::library('session')->set_userdata ( 'user_session', $user_session );
		CI::library('session')->set_userdata ( 'user', $check [0] );
		$back_to = CI::model('core')->getParamFromURL ( 'back_to' );

		$first_name = $check [0] ['first_name'];
		$last_name = $check [0] ['last_name'];
		$email = $check [0] ['email'];

		if ((trim ( $first_name ) == '') or (trim ( $last_name ) == '') or (trim ( $email ) == '')) {
			redirect ( 'users/user_action:profile' );

		}

		if ($back_to != '') {
			$back_to = base64_decode ( $back_to );

			if (trim ( $back_to ) != '') {

				header ( 'Location: ' . $back_to );

				exit ();

			} else {

				redirect ( 'dashboard' );

			}

		} else {
			redirect ( 'dashboard' );
		}

	}

}

$this->template ['user_login_errors'] = $user_login_errors;

$this->load->vars ( $this->template );

$user_session ['user_action'] = $user_action;

$content ['content_filename'] = 'users/login.php';



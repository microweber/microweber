<?php

$no_layout = true;
$curent_user_id = $this->users_model->userId ();
if (intval ( $curent_user_id ) == 0) {
	exit ( 'login required' );
}

$this->template ['user_edit_done'] = FALSE;

$reg_is_error = false;

$this->load->vars ( $this->template );

if ($_POST) {
	$errors = array ();
	$data = $this->users_model->getUserById ( $curent_user_id );
	//p ( $data );

	$old_password = $this->input->post ( 'old_password' );
	if ($data ['password'] != $old_password) {
		$errors[] = 'You have entered your curent password incorectly.';
	}
	$new_password = $this->input->post ( 'new_password' );
	$new_password2 = $this->input->post ( 'new_password2' );

	if ($new_password != $new_password2) {
		$errors[] = 'Password and Confirm password fields must be the same.';
	}
	if(!empty($errors)){
		print '<ul class="error">';
		foreach ($errors as $variable) {
			print '<li>'.$variable.'</li>';
		}
		print '</ul>';
	} else {
		$to_save = array();
		$to_save['id'] = $curent_user_id;
		$to_save['password'] = $new_password;
		$saved_id = $this->users_model->saveUser ( $to_save );
		//p($to_save);
		print 'ok';
	}
	exit ();
}

$userdata = array ();

$userdata ['id'] = $user_session ['user_id'];

$userdata = $this->users_model->getUsers ( $userdata );

$userdata = $userdata [0];

$this->template ['form_values'] = $userdata;

$this->load->vars ( $this->template );

$user_session ['user_action'] = $user_action;

$content ['content_filename'] = 'users/password.php';

?>
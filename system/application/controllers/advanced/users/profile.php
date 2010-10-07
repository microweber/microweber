<?php

$this->template ['user_edit_done'] = FALSE;
$this->template ['user_profile_edit'] = true;
$reg_is_error = false;

$this->load->vars ( $this->template );

if ($_POST) {

	$username = $this->input->post ( 'username' );
	$email = $this->input->post ( 'email' );
	$userdata_check = array ();
	$userdata_check ['username'] = $username;
	$userdata_check = $this->users_model->getUsers ( $userdata_check );
	$userdata_check = $userdata_check [0];
	if ($username != '') {
		if (! empty ( $userdata_check )) {
			if (intval ( $the_userid ) != 0) {

				if ($userdata_check ['id'] != $the_userid) {
					$reg_is_error = true;
					$user_edit_errors ['username_taken'] = 'This username is owned by another user!';

				}
			} else {
				if ($userdata_check ['id'] != $user_session ['user_id']) {

					$reg_is_error = true;

					$user_edit_errors ['username_taken'] = 'This username is owned by another user!';

				}
			}
		}
	} else {

		$reg_is_error = true;

		$user_edit_errors ['username_blank'] = 'Cannot use blank username!';

	}

	if ($email != '') {

		$userdata_check = array ();

		$userdata_check ['email'] = $email;

		$userdata_check = $this->users_model->getUsers ( $userdata_check );

		$userdata_check = $userdata_check [0];

		//var_dump ( $userdata_check );
		//var_dump ( $user_session );
		if (! empty ( $userdata_check )) {
			if ($userdata_check ['id'] != $user_session ['user_id']) {
				$reg_is_error = true;
				$user_edit_errors ['email_taken'] = 'This email is owned by another user!';
			}
		}

	} else {
		$reg_is_error = true;
		$user_edit_errors ['email_blankn'] = 'Cannot use blank email!';

	}

	if ($reg_is_error == true) {

		$this->template ['user_edit_errors'] = $user_edit_errors;

	} else {

		$to_save = $_POST;

		$to_save ['id'] = $user_session ['user_id'];

		$saved_id = $this->users_model->saveUser ( $to_save );
		$new_data = $this->users_model->getUserById ( $saved_id );

		$old_user_session = $this->session->userdata ( 'user_session' );
		//p($old_user_session,1);
		foreach ( $new_data as $k => $v ) {
			$old_user_session [$k] = $v;
		}

		$this->session->set_userdata ( 'user_session', $old_user_session );
		$this->template ['form_values'] = $new_data;



		$this->template ['user_edit_errors'] = false;

		$this->template ['user_edit_done'] = true;

		$this->load->vars ( $this->template );

	}

}

$userdata = array ();

$userdata ['id'] = $user_session ['user_id'];

$userdata = $this->users_model->getUsers ( $userdata );

$userdata = $userdata [0];

$this->template ['form_values'] = $userdata;

$this->load->vars ( $this->template );

$user_session ['user_action'] = $user_action;

$content ['content_filename'] = 'users/profile.php';

?>
<?  ?>

<?
if ($_POST) {
	$errors = array ();
	$CI = get_instance ();
	$data = $CI->users_model->getUserById ( $curent_user_id );
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
		$saved_id = $CI->users_model->saveUser ( $to_save );
		//p($to_save);
		print 'ok';
	}
	exit ();
}

?>
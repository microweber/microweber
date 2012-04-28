<?php


$user_id = $this->core_model->userId ();

if (intval ( $user_id ) != 0) {
	if ($user_action != 'profile') {

		$userdata = $this->users_model->getUserById ( $user_id );
		$first_name = $userdata ['first_name'];
		$last_name = $userdata ['last_name'];
		$email = $userdata ['email'];
		if ((trim ( $first_name ) == '') or (trim ( $last_name ) == '') or (trim ( $email ) == '')) {
			//redirect ( 'users/user_action:profile/compete_profile:yes' );
		}
	}
}
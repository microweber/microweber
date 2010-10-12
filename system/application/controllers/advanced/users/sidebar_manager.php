<?php

$this->template ['user_edit_done'] = FALSE;

$reg_is_error = false;

$this->load->vars ( $this->template );

if ($_POST) {

		$to_save = $_POST;
		$to_save ['id'] = $this->core_model->userId() ;
		$saved_id = $this->users_model->saveUser ( $to_save );
		$this->template ['user_edit_done'] = true;
		$this->load->vars ( $this->template );

}

$userdata = array ();

$userdata ['id'] = $user_session ['user_id'];

$userdata = $this->users_model->getUsers ( $userdata );

$userdata = $userdata [0];

$this->template ['form_values'] = $userdata;

$this->load->vars ( $this->template );

$user_session ['user_action'] = $user_action;

$content ['content_filename'] = 'users/sidebar_manager.php';

?>
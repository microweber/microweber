<?php
$users_list = array ();
$username = $this->core_model->getParamFromURL ( 'username' );
$users_list ['username'] = $username;

$users_list = $this->users_model->getUsers ( $users_list, false );

$this->template ['user_about'] = $users_list [0];

// $this->load->vars ( $this->template );

$content ['content_filename'] = 'users/userbase/contacts.php';


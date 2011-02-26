<?php
$users_list = array ();
$username = CI::model('core')->getParamFromURL ( 'username' );
$users_list ['username'] = $username;

$users_list = CI::model('users')->getUsers ( $users_list, false );
//p($users_list);
$this->template ['user_about'] = $users_list [0];

$this->load->vars ( $this->template );

$content ['content_filename'] = 'users/userbase/user_about.php';


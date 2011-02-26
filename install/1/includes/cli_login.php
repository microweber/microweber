<?php
$layout = 'page';

if(isset($QUERY['layout']) and $QUERY['layout'] == 'cli') { //If the script is called from the command line
	$layout = 'cli';
	if(!isset($_SESSION['user'])) { //Single user
		if(isset($PARAM['auth_username'])) {
			//Authentication happens here.
			$user_id = $sql->getOne("SELECT id FROM ${config['db_prefix']}User WHERE " 
				. "username='" . mysql_real_escape_string($PARAM['auth_username']) . "' AND "
				. "password='" . mysql_real_escape_string($PARAM['auth_password']) . "'");
			if(!$user_id) {
				print t('Invalid User/Password.')."\n\n";
				exit;
			} else {
				$_SESSION['user'] = $user_id;
				$projects = $sql->getById("SELECT id,name FROM ${config['db_prefix']}Project WHERE user_id='$user_id'");
			}
		}
	}
}

if(!isset($_SESSION['user'])) {
	header("Location:${rel}users/login.php");
	exit;
}


<?php
include('../common.php');

if(isset($_REQUEST['action']) and $_REQUEST['action'] == 'Login') {
	$user = $sql->getAssoc("SELECT id,username,password,name FROM ${config['db_prefix']}User "
			. "WHERE username='$QUERY[username]' AND password='$QUERY[password]'");

	//A secondary check - unnecessary but, ... you know ...
	if($user and ($user['username'] == $QUERY['username']) and ($user['password'] == $QUERY['password'])) {
		$_SESSION['user'] = $user['id'];
		$name = ($user['name']) ? $user['name'] : $user['username'];

		if(isset($_REQUEST['remember']) and $_REQUEST['remember']) {
			$expire = time()+30*60*60*24*30;//Will expire in 30 days
			setcookie('user',$user['username'],$expire,'/');

			$hash_string = $user['password'] . 'Xr' . $user['id'] . '@t5';//Salted!
			setcookie('hash',sha1($hash_string),$expire,'/');//Encrypted password is stored in cookie
		}
		showMessage(t('Welcome back %s',$name),'../index.php');
	}

	//Check wether they have provided a valid username
	$username = $sql->getAssoc("SELECT id FROM ${config['db_prefix']}User WHERE username='$QUERY[username]'");
	if(isset($username['id'])) {
		$QUERY['error'] = t('Invalid Password.');
	} else {
		$QUERY['error'] = t('Invalid Username. User \'%s\' does not exist in the Database.',$QUERY['username']);
	}
}

render();

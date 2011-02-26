<?php
include('../common.php');

$users = $sql->getById("SELECT id,username FROM ${config['db_prefix']}User");
//Get the Id of the first user
$first_user = each($users);
$first_user = $first_user['key'];
		
if(isset($_REQUEST['mode']) and $_REQUEST['mode'] == 'single') {
	if(count($users) == 1) {
		$sql->execQuery("UPDATE ${config['db_prefix']}Setting SET value=$first_user, status='1' WHERE name='SingleUser'");
		$QUERY['success'] = t('Mode changed to Single User Mode.');
	} else {
		$QUERY['error'] = t('Currently there are %d users in the system. There must only be one user. Please delete all other users and try again.',count($users));
	}
} elseif(isset($_REQUEST['mode']) and $_REQUEST['mode'] == 'multi') {
	$errors = check(array(
		array('name'=>'username','is'=>'empty'),
		array('name'=>'password','is'=>'empty'),
		array('name'=>'password','is'=>'not','value'=>$_REQUEST['confirm_password'],'error'=>t('Password and Confirm password fields don\'t match'))
	),2);
	
	if($errors) $QUERY['error'] = $errors;
	else { //No Validation errors
		$QUERY['added_on'] = date('Y-m-d H:i:s');
		$sql->updateFields($config['db_prefix'].'User',array('username','password','added_on'),$QUERY,"id=$first_user");// Set the user data
		$_SESSION['user'] = $first_user; //Log him in
		$sql->execQuery("UPDATE ${config['db_prefix']}Setting SET value=$first_user, status='0' WHERE name='SingleUser'"); //Switch off Single user mode
		$single_user['status'] = '0';

		$QUERY['success'] = t('Switched to multi-user mode successfully');//Show the message.
	}
}

render();
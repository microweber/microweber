<?php
include('../common.php');

if(isset($_REQUEST['action']) and $_REQUEST['action'] == t('Register')) {
	$dupliate_chk = array();
	if($sql->getOne("SELECT id FROM ${config['db_prefix']}User WHERE username='$QUERY[username]'")) {
		$dupliate_chk[] = array(
			'name'	=> 'username',
			'is'	=> 'equal',
			'value'	=> $QUERY['username'],
			'error'	=> t('User \'%s\' already exists. Please select another username.',$QUERY['username'])
		);
	}

	$errors = check($dupliate_chk + array(
		array('name'=>'username','is'=>'empty'),
		array('name'=>'password','is'=>'empty'),
		array('name'=>'password','is'=>'not','value'=>$_REQUEST['confirm_password'],'error'=>t('Password and Confirm password fields don\'t match'))
	),2);
	
	if($errors) $QUERY['error'] = $errors;
	else { //No Validation errors
		$QUERY['added_on'] = date('Y-m-d H:i:s');
		$_SESSION['user'] = $sql->insertFields($config['db_prefix'].'User',array('name','username','password','email','added_on'),$QUERY);
		
		//Create a default project for this user...
		$default_project = $sql->insert($config['db_prefix']."Project",
				array(
					'name'		=> t('Misc'),
					'created_on'=> date('Y-m-d H:i:s'),
					'user_id'	=> $_SESSION['user']
				)
			);
		$sql->update($config['db_prefix'].'User',array('default_project'=>$default_project),'id='.$_SESSION['user']);
		
		$name = ($_REQUEST['name']) ? $_REQUEST['name'] : $_REQUEST['username'];
		showMessage(t('Hello %s! Welcome to the family.',$name),'../index.php');
	}
}

$user = $_REQUEST;
if(!isset($user['name'])) $user['name'] = '';
if(!isset($user['username'])) $user['username'] = '';
if(!isset($user['email'])) $user['email'] = '';

render();
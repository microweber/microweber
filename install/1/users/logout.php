<?php
include('../common.php');

if(isset($_SESSION['user'])) {
	$user = $sql->getAssoc("SELECT name,username FROM ${config['db_prefix']}User WHERE id=$_SESSION[user]");
	unset($_SESSION['user']);
}

//Reset Cookie Data
unset($_COOKIE['user']);
unset($_COOKIE['hash']);
setcookie('user','',time()-1,'/');
setcookie('hash','',time()-1,'/');

$_REQUEST['username'] = '';
if(isset($_REQUEST['username'])) $_REQUEST['username'] = $user['username'];
if(isset($user['name'])) {
	$name = ($user['name']) ? $user['name'] : $user['username'];
	$QUERY['success'] = t('User logged out. Goodbye %s',$name);
}

$pending_projects = array();
$contexts = array();
$projects = array();
$todays_reminder = array();
$reminder_alerts = array();

render();
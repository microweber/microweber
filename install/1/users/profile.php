<?php 
include('../common.php');
if(!isset($_SESSION['user'])) {
	showMessage(t('Please login to continue'),'login.php','error');
}

//Update the db according to the data inputed by the user.
if(isset($_REQUEST['action']) and $_REQUEST['action'] == t('Edit Profile')) {
	$change_items = array('name','email');
	if($_REQUEST['password']) $change_items[] = 'password';

	$sql->updateFields($config['db_prefix'].'User',$change_items,$QUERY,"id='$_SESSION[user]'");
	$QUERY['success'] = t('Profile updated successfully');

//Deleting the user...
} elseif(isset($_REQUEST['action']) and $_REQUEST['action'] == 'Delete This User') {
	$sql->execQuery("DELETE FROM ${config['db_prefix']}Task WHERE user_id=$_SESSION[user]");		//Delete all tasks
	$sql->execQuery("DELETE FROM ${config['db_prefix']}Project WHERE user_id=$_SESSION[user]");	//Delete all Projects
	$sql->execQuery("DELETE FROM ${config['db_prefix']}Context WHERE user_id=$_SESSION[user]");	//Contexts
	$sql->execQuery("DELETE FROM ${config['db_prefix']}TaskContext WHERE user_id=$_SESSION[user]");//Context/Task Relation.
	$sql->execQuery("DELETE FROM ${config['db_prefix']}Reminder WHERE user_id=$_SESSION[user]");//Context/Task Relation.
	$sql->execQuery("DELETE FROM ${config['db_prefix']}Setting WHERE user_id='$_SESSION[user]'");//Context/Task Relation.
	$sql->execQuery("DELETE FROM ${config['db_prefix']}User WHERE id=$_SESSION[user] LIMIT 1");	//Finally, delete the user.

	//And log him out.
	unset($_SESSION['user']);
	unset($_COOKIE['user']);
	showMessage(t('User deleted sucessfully!'),'login.php');
}

$user = $sql->getAssoc("SELECT * FROM ${config['db_prefix']}User WHERE id='$_SESSION[user]'");

render();

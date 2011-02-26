<?php
include('../common.php');

// To make sure that the default project does not get deteted
$default_project = $sql->getOne("SELECT default_project FROM ${config['db_prefix']}User WHERE id='$_SESSION[user]'");

if(isset($QUERY['project']) and is_numeric($QUERY['project']) and $QUERY['project']!=$default_project) {
	$project_name = $projects[$QUERY['project']];
	$Project->remove($QUERY['project']);
	
	showMessage(t('Project \'%s\' deleted successfully',$project_name),'index.php');
}
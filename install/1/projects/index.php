<?php
include('../common.php');
include('../includes/classes/SqlPager.php');

//We bring in the pager.
$pager = new SqlPager("SELECT id,name FROM ${config['db_prefix']}Project WHERE user_id='$_SESSION[user]'");
$projects_sql = $pager->getSql();
$projects = array();
while($projects_parts = $sql->fetchRow($projects_sql)) {
	$projects[$projects_parts[0]] = $projects_parts[1];
}

//To make sure that the default project does not get deteted
$default_project = $sql->getOne("SELECT default_project FROM ${config['db_prefix']}User WHERE id='$_SESSION[user]'");

$template->addResource('_single_line_list.js');
render();
<?php
include('./common.php');
include('includes/cli_login.php');

$tasks = $sql->getAll("SELECT T.id,T.name,T.project_id,P.name as project_name "
		. " FROM ${config['db_prefix']}Task As T INNER JOIN ${config['db_prefix']}Project AS P on project_id=P.id "
		. " WHERE T.type='Immediately' AND P.user_id=$_SESSION[user] ORDER BY T.sort_order, T.due_on DESC, T.created_on DESC");

//This is the output for the shell
if($layout == 'cli') {
	print "Stuff To Do\n-----------\n";
	foreach($tasks as $task) {
		print trim($task['name']) . " (" . $projects[$task['project_id']] . ")\n";
	}
	exit;
}

render();

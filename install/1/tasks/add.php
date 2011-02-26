<?php
include('../common.php');
include('../includes/cli_login.php');

$task_count = 0;
if(!isset($PARAM['task']) or empty($PARAM['task']) or $PARAM['task'] == '%s') {
	if($layout == 'cli') {
		print t('No Tasks were specified').'\n'.t('Usage : nexty "Task name"').'\n\n';
		exit;
	} else {
		showMessage(t('No Tasks were specified'),"inbox.php",'error');
	}
}

$tasks = $PARAM['task'];
$project_for_all_tasks = i($PARAM, 'project');

$all_tasks = explode("\n",$tasks);
$default_project = $sql->getOne("SELECT default_project FROM ${config['db_prefix']}User WHERE id='$_SESSION[user]'");

foreach($all_tasks as $task) {
	if(!trim($task)) continue; //Empty line
	
	if($project_for_all_tasks) $project_id = $project_for_all_tasks; //User set a Project to which all the tasks should belong to.
	else $project_id = findProject($task);
	
	// Still no project - auto find did not get anythng.
	if(!$project_id) $project_id = $default_project; //If no project was found, give it the default project 'Misc'

	if($task_id = $Task->create($task,$project_id)) {
		$task_count++;
		$added_tasks[] = array(
			'task' => $task,
			'task_id' => $task_id,
			'project_id' => $project_id
		);
	}
}

$QUERY['success'] = t('%d Task(s) created',$task_count);

//This is the output for the shell
if($layout == 'cli') {
	print t('Added Tasks') . 'n-----------\n';
	foreach($added_tasks as $at) {
		print $at['task'] . t(' in ') . $projects[$at['project_id']]. '\n';
	}
	print "\n$QUERY[success]\n\n";
	exit;
}

//This will not happen in the shell - that had exited
render();

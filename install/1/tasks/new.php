<?php
include('../common.php');

if(isset($QUERY['action']) and $QUERY['action'] == t('Create')) {
	if(isset($QUERY['name'])) {
		$contexts = isset($QUERY['contexts']) ? $QUERY['contexts'] : array();
		$new_task_id = $Task->create($PARAM['name'],$PARAM['project_id'],$PARAM['type'],$PARAM['description'],$PARAM['url'],$contexts, $PARAM['due_on']);
		if($new_task_id)
			showMessage(t('Task \'%s\' created successfully',$PARAM['name']),"list.php?project=$QUERY[project_id]");
		else
			$QUERY['error'] =t('Cannot create task due to interal error');
	} else {
		$QUERY['error'] = t('Please enter the name of the task');
	}
}

$project_id = 0;
$project_name = '';
$type = 'Immediately';

if(isset($QUERY['project'])) {
	$project_id = $QUERY['project'];
	$project_name = $projects[$project_id];
}
if(isset($QUERY['type'])) {
	$type = $QUERY['type'];
}
$task = $PARAM;

//Fill in the default values
$task['project_id'] = isset($task['project_id']) ? $task['project_id'] : 0 ;
$task['name'] = isset($task['name']) ? $task['name'] : '' ;
$task['description'] = isset($task['description']) ? $task['description'] : '' ;
$task['url'] = isset($task['url']) ? $task['url'] : '' ;
$task['type'] = isset($task['type']) ? $task['type'] : 'Immediately';
$task['due_on'] = isset($task['due_on']) ? $task['due_on'] : '';
$task['contexts'] = isset($task['context']) ? array($task['context']) : array();

$template->addResource('libraries/calendar.js','js');
$template->addResource('libraries/calendar.css','css');
$template->addResource('tasks/form.css','css');
$template->addResource('tasks/form.js','js');

render();

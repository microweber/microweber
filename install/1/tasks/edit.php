<?php
include('../common.php');

$task = $sql->getAssoc("SELECT id,name,description,type,url,project_id,edited_on, DATE_FORMAT(due_on, '%Y-%m-%d') AS due_on,"
	. "DATE_FORMAT(created_on,'$config[date_format]') AS created_on,DATE_FORMAT(completed_on,'$config[date_format]') AS completed_on "
	. "FROM ${config['db_prefix']}Task WHERE id='$QUERY[task]' AND user_id='$_SESSION[user]'");
if(!$task) {
	showMessage(t('No such task'),"list.php","error");
}
if($task['due_on'] == '0000-00-00') $task['due_on'] = '';

$task['contexts'] = $sql->getCol("SELECT context_id FROM ${config['db_prefix']}TaskContext WHERE task_id=$task[id] AND user_id='$_SESSION[user]'");

if(isset($QUERY['action']) and $QUERY['action']=='done' and $QUERY['task']) { //Mark a task as 'Done'
	$Task->complete($QUERY['task']);
	showMessage(t('Task `%s` completed!',$task['name']),"list.php?project=$task[project_id]","success");

} elseif(isset($QUERY['action']) and $QUERY['action']==t('Save') and !empty($QUERY['name'])) { //Provide the data from the form
	$QUERY['description'] = str_replace(array("\r\n", "\n", "\r"),'<br />',$QUERY['description']);//nl2br will not work as we used mysql_real_escape_string()
	
	$contexts = isset($QUERY['contexts']) ? $QUERY['contexts'] : array();
	$Task->edit($QUERY['task'], $QUERY['name'],$QUERY['project_id'],$QUERY['type'], $QUERY['description'],$QUERY['url'],$contexts, $QUERY['due_on']);

	if(isset($QUERY['project_id'])) {
		showMessage(t('Task `%s` updated successfully',$PARAM['name']),"list.php?project=$QUERY[project_id]","success");
	} else {
		showMessage(t('Task `%s` updated successfully',$PARAM['name']));
	}
} else {
	$template->addResource('libraries/calendar.js','js');
	$template->addResource('libraries/calendar.css','css');
	$template->addResource('tasks/form.css','css');
	$template->addResource('tasks/form.js','js');
	render();
}
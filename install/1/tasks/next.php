<?php
include('../common.php');

$action_title = '';
if(isset($_REQUEST['project']) and $_REQUEST['project']) {// Select Tasks based on projects
	$task_data = $sql->getAll("SELECT id,name,description,url,edited_on,created_on,project_id,sort_order "
			. "FROM ${config['db_prefix']}Task WHERE type='Immediately' AND user_id='$_SESSION[user]' AND project_id='$QUERY[project]' "
			. "ORDER BY sort_order, created_on DESC");
	$action_title = t(' in ') . $projects[$QUERY['project']];

} elseif(isset($_REQUEST['context']) and $_REQUEST['context']) { // Select Tasks based on context
	$task_data = $sql->getAll("SELECT id,name,description,url,edited_on,created_on,project_id,sort_order "
			. "FROM ${config['db_prefix']}Task AS Task INNER JOIN ${config['db_prefix']}TaskContext AS TaskContext ON Task.id=TaskContext.task_id "
			. "WHERE type='Immediately' AND Task.user_id='$_SESSION[user]' AND TaskContext.context_id='$QUERY[context]' "
			. "ORDER BY sort_order, created_on DESC");
	$action_title = " ".t('in Context')." '" . $contexts[$QUERY['context']] . "'";
	
} else {
	$task_data = $sql->getAll("SELECT id,name,description,url,edited_on,created_on,project_id,sort_order "
			. "FROM ${config['db_prefix']}Task WHERE type='Immediately' AND user_id='$_SESSION[user]' "
			. "ORDER BY sort_order, created_on DESC");
}

if(!$task_data) {
	$QUERY['success'] = t('Nothing to do - go browse the webs!');
}

// Convert bbcode to html
$bbcode = new BBcode();
for($i=0; $i<count($task_data); $i++) {
	$task_data[$i]['description'] = $bbcode->Parse($task_data[$i]['description']);
}

$template->addResource('bbcode.css','css');
$template->addResource('bbcode.js','js');

render();
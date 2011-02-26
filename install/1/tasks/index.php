<?php
include('../common.php');
include('../includes/classes/SqlPager.php');

$pager = new SqlPager("SELECT T.id,T.name,T.description,T.url,T.type, "
		. " DATE_FORMAT(T.due_on,'$config[date_format]') AS due_on, "
		. " DATE_FORMAT(T.edited_on,'$config[date_format]') AS time,"
		. " T.project_id, P.name as project_name "
		. " FROM ${config['db_prefix']}Task As T INNER JOIN ${config['db_prefix']}Project AS P on project_id=P.id "
		. " WHERE T.type='Immediately' AND P.user_id='$_SESSION[user]' "
		. " ORDER BY T.due_on DESC, T.edited_on DESC",10);
$tasks = $pager->getPage();
$change_order = false;

$bbcode = new BBcode();

//Get the project name and context names into the $tasks array
for($i=0; $i<count($tasks); ++$i) {
	$tasks[$i]['description'] = $bbcode->Parse($tasks[$i]['description']);
}

$template->addResource('bbcode.css','css');
$template->addResource('bbcode.js','js');

$template->addResource('_list.js','js');
$template->addResource('tasks/list.js','js');

render();
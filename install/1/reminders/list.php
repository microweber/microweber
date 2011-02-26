<?php
include('../common.php');
include('../includes/classes/SqlPager.php');

$reminder_qry = "SELECT id,name,description,DATE_FORMAT(created_on,'$config[date_format]') as created_on,DATE_FORMAT(day,'$config[date_format]') as day "
		. " FROM ${config['db_prefix']}Reminder WHERE user_id='$_SESSION[user]' ORDER BY day";
		
if(isset($QUERY['month'])) {
	$reminder_qry = "SELECT id,name,description,"
		. " DATE_FORMAT(created_on,'$config[date_format]') as created_on,DATE_FORMAT(day,'$config[date_format]') as day "
		. " FROM ${config['db_prefix']}Reminder WHERE MONTH(day)=$QUERY[month] AND user_id='$_SESSION[user]' ORDER BY day";
}

$pager = new SqlPager($reminder_qry,10);
$reminders = $pager->getPage();

 // Convert bbcode to html
$bbcode = new BBcode();
for($i=0; $i<count($reminders); $i++) {
	$reminders[$i]['description'] = $bbcode->Parse($reminders[$i]['description']);
}

$template->addResource('bbcode.css','css');
$template->addResource('bbcode.js','js');

$template->addResource('_list.js','js');
render();

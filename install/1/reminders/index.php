<?php
include('../common.php');
include('../includes/classes/SqlPager.php');

//Show all the reminders in this month
$reminder_qry = "SELECT id,name,created_on,day,description FROM ${config['db_prefix']}Reminder "
	. " WHERE MONTH(CURDATE())=MONTH(day) AND day>=CURDATE()"
	. " AND user_id='$_SESSION[user]' ORDER BY day";

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
$template->addResource('reminders/list.css','css');
$template->addResource('reminders/list.js','js');
render();
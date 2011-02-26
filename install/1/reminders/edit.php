<?php
include('../common.php');

$reminder_data = $sql->getAssoc("SELECT id,name,description,day FROM ${config['db_prefix']}Reminder WHERE id=$QUERY[reminder]");

if(isset($QUERY['action']) and $QUERY['action']==t('Edit') and $QUERY['name']) { //Provide the data from the form
	$Reminder->edit($PARAM['reminder'], $PARAM['name'], $PARAM['day'], str_replace(array("\r\n", "\n", "\r"), "<br />",$PARAM['description']));
	$month = intval(date('m',strtotime($QUERY['day'])));
	showMessage(t('Reminder \'%s\' updated successfully',$QUERY['name']),"list.php?month=$month","success");
} else {
	$template->addResource('libraries/calendar.js','js');
	$template->addResource('libraries/calendar.css','css');
	$template->addResource('reminders/form.css','css');
	$template->addResource('reminders/form.js','js');
	render();
}
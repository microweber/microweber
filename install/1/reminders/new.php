<?php
include('../common.php');

if(isset($QUERY['action']) and $QUERY['action']==t('Create')) {
	if($QUERY['name'] and $QUERY['day']) {
		$Reminder->create($PARAM['name'], $PARAM['day'], $PARAM['description']);
		showMessage(t('Reminder \'%s\' created successfully',$QUERY['name']),"index.php");
	} else {
		$QUERY['error'] = t('Please enter the name and the day of the task');
	}

	$reminder_data  = $PARAM;
} else {
	$reminder_data = array(
		'name'=>'',
		'description'=>'',
		'day'=>date('Y-m-d')
	);
}

$template->addResource('libraries/calendar.js','js');
$template->addResource('libraries/calendar.css','css');
$template->addResource('reminders/form.css','css');
$template->addResource('reminders/form.js','js');

render();

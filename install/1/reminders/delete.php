<?php
include('../common.php');

if(isset($QUERY['reminder']) and is_numeric($QUERY['reminder'])) {
	$reminder_data = $Reminder->find($QUERY['reminder'],array('select'=>'name'));
	$Reminder->remove($QUERY['reminder']);

	showMessage(t('Reminder \'%s\' deleted successfully',$reminder_data['name']),'index.php');
}
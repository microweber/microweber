<?php
include('../common.php');

if(isset($QUERY['task']) and is_numeric($QUERY['task'])) {
	$task_name = $Task->find($QUERY['task'],array("select"=>'name'));
	$task_name = $task_name['name'];
	$Task->remove($QUERY['task']);

	showMessage(t('Task \'%s\' deleted successfully',$task_name),'list.php');
}
<?php
include('../common.php');

if(isset($QUERY['context']) and is_numeric($QUERY['context'])) {
	$context = $contexts[$QUERY['context']];
	
	$Context->remove($QUERY['context']);
	showMessage(t('Context \'%s\' deleted successfully',$context),'index.php');
}
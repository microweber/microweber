<?php
include('../common.php');

if(isset($QUERY['action']) and $QUERY['action']==t('Create')) {
	if(!$QUERY['name'])	showMessage(t('Please provide the name of the new context'),'','error');

	$Context->checkDuplicate($QUERY['name']); //Check for duplicate

	if($id = $Context->create($QUERY['name'])) {
		showMessage(t('Context \'%s\' created successfully',$QUERY['name']),'index.php','success',$id);
	}
}

render();
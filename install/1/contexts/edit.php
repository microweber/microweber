<?php
include('../common.php');

//Get the context data of the given context id.
if(isset($QUERY['context'])) 
	$context_data = $Context->find($QUERY['context'], array('select'=>array('id','name')));

if(isset($QUERY['action']) and $QUERY['action'] == t('Edit')) {
	if(!$QUERY['name'])	showMessage(t('Please provide the new name'),'?context='.$QUERY['context'],'error');

	$Context->checkDuplicate($QUERY['name'], $QUERY['context']); //Check for duplicate

	if($Context->rename($QUERY['context'],$QUERY['name'])) {
		showMessage(t('Context \'%s\' updated successfully',$QUERY['name']),"index.php?");
	}
} else {
	render();
}
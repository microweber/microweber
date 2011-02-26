<?php
include('../common.php');

$project = array('id'=>$QUERY['project'],'name'=>$projects[$QUERY['project']]);

if(isset($QUERY['action']) and $QUERY['action']==t('Edit')) {
	if(!$QUERY['name'])	showMessage(t('Please provide the new name'),'?project='.$QUERY['project'],'error');

	if($Project->edit($QUERY['project'], $QUERY['name'])) {
		showMessage(t('Project \'%s\' updated successfully',$projects[$QUERY['project']]),'index.php');
	} else {
		showMessage(t('No changes made'),'index.php','error');
	}
} else {
	render();
}
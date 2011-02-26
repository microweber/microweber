<?php
include('../common.php');

if(isset($QUERY['action']) and $QUERY['action']==t('Create')) {
	if(!$QUERY['name'])	showMessage(t('Please provide the name of the new project'),'','error');

	if($id = $Project->create($QUERY['name'])) {
		showMessage(t('Project \'%s\' created successfully',$PARAM['name']),"index.php",'success',$id);
	}
}

render();
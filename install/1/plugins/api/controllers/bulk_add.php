<?php 

if(isset($QUERY['action']) and $QUERY['action']==t('Create')) {
	for($i=0; $i<count($QUERY['name']); $i++) {
		$task = array(
			'name'			=> $QUERY['name'][$i],
			'created_on'	=> date('Y-m-d H:i:s'),
			'edited_on'		=> date('Y-m-d H:i:s'),
			'description'	=> $QUERY['description'][$i],
			'url'			=> $QUERY['url'][$i],
			'type'			=> $QUERY['type'][$i],
			'project_id'	=> $QUERY['project_id'][$i],
			'user_id'		=> $_SESSION['user']
		);

		$fields = array('name','description','type','url','created_on','edited_on','project_id','user_id');

		$id = $sql->insertFields($config['db_prefix'].'Task',$fields,$task);

		//Currently contexts are not supported in bulk add.
		if($id) {
			print "<div class'success'>Task '$task[name]' created successfully</div>";
		} else {
			print "<div class'error'>Cannot create task '$task[name]'. Seems like I made a mistake somewhere.</div>";
		}
	}
}

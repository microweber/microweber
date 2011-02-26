<?php
include_once('../includes/classes/ORM.php');

/**
 * Model for Projects
 */

class Project extends DBTable {
	function __construct() {
		global $config;
		parent::__construct($config['db_prefix']."Project");
	}

	/**
	 * Create a new project if it does not exist.
	 * Argument : $name - The name of the new project.
	 * Return : The ID of the newly created project
	 */
	function create($name) {
		if(!$name) return -1;
		global $projects;

		if($this->checkDuplicate($name)) {
			showMessage(t('Project \'%s\' already exists!',$name),"index.php",'error');
		}

		$this->newRow();
		$this->field['name'] = $name;
		$this->field['user_id'] = $_SESSION['user'];
		$this->field['created_on'] = 'NOW()';
		$id = $this->save();
		$projects[$id] = $name;
		return $id;
	}
	
	/**
	 * Renames the given project.
	 * Argument : $project_id - The ID of the project that must be renamed
	 *			  $new_name - The new name of the project
	 */
	function edit($project_id, $new_name) {
		if(!$project_id or !$new_name) return -1;
		
		global $projects;
		if($projects[$project_id] == $new_name) return 0;//No change in name
	
		if($this->checkDuplicate($new_name, $project_id)) {
			showMessage(t('Project \'%s\' already exists!',$new_name),"index.php",'error');
		}
		
		$this->newRow($project_id);
		$this->field['name'] = $new_name;
		
		$projects[$project_id] = $new_name;
		return $this->save();
	}
	
	/**
	 * Deletes the project with the given ID. Also deletes all the tasks in this project.
	 * Argument : $id - The ID of the project that must be deleted.
	 */
	function remove($id) {
		if(!$id) return -1;
		
		global $projects, $sql;
	
		if(!isset($projects[$id])) return 0;
		
		$this->where("user_id='$_SESSION[user]'", "id=$id");
		$this->delete();
		if(!$sql->fetchAffectedRows()) return 0;
		unset($projects[$id]);
		
		//Remove all the tasks in that project
		include('Tasks.php');
		global $Task;
		$project_tasks = $Task->find(array("select"=>"id", "where"=>"project_id=$id"));
		foreach($project_tasks as $task_info) {
			$Task->delete($task_info['id']);
		}
	}
	
	/**
	 * Checks wether the given project already exists.
	 * Arguments : $name - The name of the project to be searched for
	 *				$not_id - [Optional] This ID is an exception. The name can exist with this ID can exist without triggering an error.
	 */
	function checkDuplicate($name, $not_id=0) {
		//See if a project with that name is already there.
		global $projects;
		foreach($projects as $id=>$project_name) {
			if($project_name == $name and $id != $not_id) return $id;
		}
		return 0;
	}
}
$GLOBALS['Project'] = new Project();

/*
$project_id = $Project->create('New Test Project');
$project_id = 1;
print $Project->rename($project_id, 'Old Test Project');
$Project->delete($project_id);
*/
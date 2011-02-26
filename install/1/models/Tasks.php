<?php
include_once('../includes/classes/ORM.php');

class Task extends DBTable {
	function __construct() {
		global $config;
		parent::__construct($config['db_prefix']."Task");
	}

	/**
	 * Create a new task with the details given as arugments. Also set the contexts of the task.
	 */
	function create($name, $project_id, $type='Immediately', $description='', $url='', $context_ids=array(), $due_date='') {
		global $config;
		if(!$name or !$project_id) return -1;

		global $projects, $sql;
		if(!isset($projects[$project_id])) $project_id = 1;//If the project does not exist, give it the default value

		$this->newRow();
		$this->field['name'] = $name;
		$this->field['project_id'] = $project_id;
		$this->field['type'] = $type;
		$this->field['user_id'] = $_SESSION['user'];
		$this->field['created_on'] = 'NOW()';
		$this->field['edited_on'] = 'NOW()';
		$this->field['due_on'] = $due_date;
		if($description) $this->field['description'] = $description;
		if($url) $this->field['url'] = $url;
		$new_task_id = $this->save();

		if($new_task_id and $context_ids) { //Inserts the Context as well
			foreach($context_ids as $context) {
				$sql->insert($config['db_prefix'].'TaskContext',
								array(
									'task_id'		=> $new_task_id,
									'context_id'	=> $context,
									'user_id'		=> $_SESSION['user']
								)
							);
			}
		}
		return $new_task_id;
	}

	/**
	 * Edit an existing task with the given data.
	 * Arguments:
	 *		$task_id - The ID of the task that we are going to edit.
	 *		$name - The name of the task
	 *		$project_id - The ID of the project this task belongs to
	 *		$type [Optional] - The task type - could be 'Immediately', 'Done', 'Idea', 'Waiting' etc.
	 *		$description [Optional] - A small description of this task.
	 *		$url [Optional] - A url that must me attached to this task - if any.
	 *		$context_ids [Optional] - All the contexts this task belongs to
	 */
	function edit($task_id, $name, $project_id, $type='Immediately', $description='', $url='', $context_ids=array(), $due_date) {
		global $config;
		if(!$task_id or !$name or !$project_id) return -1;

		global $projects,$sql;
		if(!isset($projects[$project_id])) $project_id = 1;//If the project does not exist, give it the default value

		$this->newRow($task_id);
		$this->field['name'] = $name;
		$this->field['project_id'] = $project_id;
		$this->field['type'] = $type;
		$this->field['user_id'] = $_SESSION['user'];
		$this->field['description'] = $description;
		$this->field['url'] = $url;
		$this->field['due_on'] = $due_date;
		$this->save();

		$sql->execQuery("DELETE FROM ${config['db_prefix']}TaskContext WHERE user_id='$_SESSION[user]' AND task_id='$task_id'"); //First remove the existing contexts
		if($context_ids) { //Inserts the Context as well
			foreach($context_ids as $context) {
				$sql->insert($config['db_prefix'].'TaskContext',
								array(
									'task_id'		=> $task_id,
									'context_id'	=> $context,
									'user_id'		=> $_SESSION['user']
								)
							);
			}
		}
	}

	/**
	 * Deletes the given task along with all its context associations
	 */
	function remove($task_id) {
		if(!$task_id) return -1;
		global $config;

		$this->where("user_id='$_SESSION[user]'");
		if(!$this->delete($task_id)) return 0;

		//Remove all the contexts of that Task.
		$TaskContext = new DBTable($config['db_prefix'] . 'TaskContext');
		$TaskContext->where("context_id=$task_id","user_id='$_SESSION[user]'");
		$TaskContext->delete();
	}

	/**
	 * Mark the given task as complete and set the completed_on time as now.
	 */
	function complete($task_id) {
		if(!$task_id) return -1;

		$this->newRow($task_id);
		$this->where("user_id='$_SESSION[user]'");
		$this->field['type'] = 'Done';
		$this->field['completed_on'] = 'NOW()';
		$this->save();
	}
	
	/**
	 * Reset the sort_order of all the tasks given it the arugment. The argument must be in this format
	 * array(
	 *	id	=> new order value,
	 *	id	=> new order value,
	 *	etc..
	 * )
	 */
	function reOrder($order) {
		foreach($order as $task_id => $sort_order) {
			$this->newRow($task_id);
			$this->where("user_id='$_SESSION[user]'");
			$this->field['sort_order'] = $sort_order;
			$this->save();
		}
	}
}

$GLOBALS['Task'] = new Task();
/*
$project_id = 5;
$task_id = $Task->create('New Test Task', $project_id, 'Immediately', 'This is the test description for the task','http://www.binnyva.com/', array(1,2,3));
$task_id = 1;
$Task->edit($task_id, 'Old Test Task', $project_id, 'Immediately', 'This is the edited description for the task','http://blog.binnyva.com/');
$Task->complete($task_id);
$Task->remove($task_id);
*/
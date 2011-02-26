<?php
include('../includes/classes/ORM.php');

class Reminder extends DBTable {
	function __construct() {
		global $config;
		parent::__construct($config['db_prefix']."Reminder");
	}

	/**
	 * Create a new reminder
	 * Argument : $name - The name of the new reminder.
	 *				$day - The reminder day
	 *				$description - [Optional] The description of the reminder.
	 */
	function create($name, $day, $description='') {
		$this->newRow();
		$this->field['name'] = $name;
		$this->field['day'] = $day;
		$this->field['created_on'] = 'NOW()';
		$this->field['modified_on'] = 'NOW()';
		$this->field['user_id'] = $_SESSION['user'];
		if(!empty($description)) $this->field['description'] = $description;
		$this->save();
	}
	
	/**
	 * Edit/Overwrite an existing reminder with the given data
	 * Arguments : $reminder_id - The ID of the reminder to be edited
	 *				$name - The new name of the reminder
	 *				$day - The new date
	 *				$description - The new description(What where you expecting?)
	 */
	function edit($reminder_id, $name, $day, $description='') {
		$this->newRow($reminder_id);
		$this->field['name'] = $name;
		$this->field['day'] = $day;
		$this->field['description'] = $description;
		$this->where("user_id='$_SESSION[user]'");
		$this->save();
	}
	
	/**
	 * Deletes the given reminder
	 * Argument : $reminder_id - The ID or the reminder that must be deleted
	 */
	function remove($reminder_id) {
		if(!$reminder_id) return -1;
		
		$this->where("user_id='$_SESSION[user]'");
		if(!$this->delete($reminder_id)) return 0;
	}
}
$GLOBALS['Reminder'] = new Reminder();
	
/*
$reminder_id = $Reminder->create("Go to Arun's house", '2007-06-20');
$reminder_id = 1;
$Reminder->edit($reminder_id, "Go to Arun's house", '2007-06-20', 'And come back');
$Reminder->remove($reminder_id);
*/
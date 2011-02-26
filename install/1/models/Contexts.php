<?php
include('../includes/classes/ORM.php');
/**
 * Model for Contexts
 * Table : Context
 */

class Context extends DBTable {
	function __construct() {
		global $config;
		parent::__construct($config['db_prefix'] . "Context");
	}

	/**
	 * Create a new context if it does not exist.
	 * Argument : $name - The name of the new context.
	 * Return : The ID of the newly created context, or the context with the given name if it already exists
	 */
	function create($name) {
		if(!$name) return false;
		global $contexts;
	
		//See if a context with that name exists
		foreach($contexts as $id=>$context_name) {
			if($context_name == $name) return $id;
		}

		//Create the new context
		$this->newRow();
		$this->field['name'] = $name;
		$this->field['user_id'] = $_SESSION['user'];
		$id = $this->save();
		$contexts[$id] = $name;
		return $id;
	}
	
	/**
	 * Renames the given context.
	 * Argument : $context_id - The ID of the context that must be renamed
	 *			  $new_name - The new name of the context
	 */
	function rename($context_id, $new_name) {
		if(!$context_id or !$new_name) return -1;
		
		global $contexts;
		if($contexts[$context_id] == $new_name) return 0;//No change in name
	
		//See if a context with that name is already there.
		foreach($contexts as $id=>$context_name) {
			if($context_name == $new_name) return $id;
		}
	
		$this->newRow($context_id);
		$this->field['name'] = $new_name;
		$this->where("user_id=".$_SESSION['user']);

		$contexts[$context_id] = $new_name;
		return $this->save();
	}
	
	/**
	 * Deletes the context with the given ID
	 * Argument : $id - The ID of the context that must be deleted.
	 */
	function remove($id) {
		if(!$id) return -1;
		
		global $contexts, $sql, $config;
		if(!isset($contexts[$id])) return 0;
		
		$this->where("user_id='$_SESSION[user]'");
		$this->delete($id);
		if(!$sql->fetchAffectedRows()) return 0;
		unset($contexts[$id]);
		
		//Remove all the tasks in that project
		$TaskContext = new DBTable($config['db_prefix'] . 'TaskContext');
		$TaskContext->where("context_id=$id","user_id='$_SESSION[user]'");
		$TaskContext->delete();
	}
	
	/**
	 * Checks wether the given context already exists. If it exist, show an error message.
	 * Arguments : $context_name - The name of the context to be searched for
	 *				$not_id - [Optional] This ID is an exception. The name can exist with this ID can exist without triggering an error.
	 */
	function checkDuplicate($context_name, $not_id=0) {
		global $sql, $config;
		if($sql->getOne("SELECT id FROM ${config['db_prefix']}Context WHERE name='$context_name' AND id!='$not_id' AND user_id='$_SESSION[user]'")) {
			showMessage(t('Context \'%s\' already exists!',$context_name),"index.php",'error');
		}
	}
}
$GLOBALS['Context'] = new Context();

/*
$context_id = $Contexts->create("New Test Context");
$context_id = 1;
$Contexts->rename($context_id,"Old Test Context");
$Contexts->remove($context_id);
*/

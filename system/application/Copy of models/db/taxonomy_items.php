<?php

$table_name = false;
$table_name = TABLE_PREFIX . "taxonomy_items";
$query = CI::db()->query ( "show tables like '$table_name'" );
$query = $query->row_array ();
$query = (array_values ( $query ));

if ($query [0] != $table_name) {
	$sql = "CREATE TABLE " . $table_name . " (
		id int(11) NOT NULL auto_increment,
		UNIQUE KEY id (id)
		);";
	CI::db()->query ( $sql );
}

$sql = "show tables like '$table_name'";
$query = CI::db()->query ( $sql );
$query = $query->row_array ();
$query = (array_values ( $query ));
if ($query [0] == $table_name) {
	 
	$fields_to_add = array ( );
	
	$fields_to_add [] = array ('parent_id', 'int(11) default NULL' );
	$fields_to_add [] = array ('to_table', 'varchar(35) default NULL' );
	$fields_to_add [] = array ('to_table_id', 'int(11) default NULL' );
	$fields_to_add [] = array ('content_type', 'varchar(35) default NULL' );
	$fields_to_add [] = array ('taxonomy_type', 'varchar(35) default NULL' );
	
	
 
	$this->set_db_tables($table_name, $fields_to_add );
	
	$this->addIndex('parent_id', $table_name, array('parent_id'));
	$this->addIndex('to_table', $table_name, array('to_table'));
	$this->addIndex('to_table_id', $table_name, array('to_table_id'));
	$this->addIndex('content_type', $table_name, array('content_type'));
	$this->addIndex('taxonomy_type', $table_name, array('taxonomy_type'));
}
?>
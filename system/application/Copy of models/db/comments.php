<?php

$table_name = false;
$table_name = TABLE_PREFIX . "comments";
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
	//$columns = $db->fetchAll("show columns from $table_name");
	/*
	$sql = "show columns from $table_name";
	$query = CI::db()->query ( $sql );
	$columns = $query->result_array ();
	
	$exisiting_fields = array ( );
	foreach ( $columns as $fivesdraft ) {
		$fivesdraft = array_change_key_case ( $fivesdraft, CASE_LOWER );
		$exisiting_fields [strtolower ( $fivesdraft ['field'] )] = true;
	}
	*/
	
	$fields_to_add = array ();
	
	$fields_to_add [] = array ('to_table', 'varchar(1500) default NULL' );
	$fields_to_add [] = array ('to_table_id', 'int(11) default NULL' );
	$fields_to_add [] = array ('updated_on', 'datetime default NULL' );
	$fields_to_add [] = array ('created_on', 'datetime default NULL' );
	$fields_to_add [] = array ('comment_name', "TEXT  character set utf8  default NULL" );
	$fields_to_add [] = array ('comment_body', "TEXT character set utf8  default NULL" );
	$fields_to_add [] = array ('comment_email', "TEXT character set utf8  default NULL" );
	$fields_to_add [] = array ('comment_website', "TEXT character set utf8  default NULL" );
	$fields_to_add [] = array ('for_newsletter', 'char(1) default "n"' );
	$fields_to_add [] = array ('is_moderated', 'char(1) default "n"' );
	
	$fields_to_add [] = array ('created_by', 'int(11) default NULL' );
	$fields_to_add [] = array ('edited_by', 'int(11) default NULL' );
	$fields_to_add [] = array ('session_id', 'VARCHAR(255) default NULL' );
	/*
	foreach ( $fields_to_add as $the_field ) {
		$sql = false;
		$the_field [0] = strtolower ( $the_field [0] );
		if ($exisiting_fields [$the_field [0]] != true) {
			$sql = "alter table $table_name add column {$the_field[0]} {$the_field[1]} ";
			CI::db()->query ( $sql );
		} else {
			$sql = "alter table $table_name modify {$the_field[0]} {$the_field[1]} ";
			CI::db()->query ( $sql );
		}
	
	}
	*/
	$this->set_db_tables ( $table_name, $fields_to_add );
	
	$sql = "alter table $table_name CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci; ";
	CI::db()->query ( $sql );
	
	$this->addIndex ( 'to_table', $table_name, array ('to_table' ) );
	$this->addIndex ( 'to_table_id', $table_name, array ('to_table_id' ) );
	$this->addIndex ( 'created_by', $table_name, array ('created_by' ) );
	$this->addIndex ( 'is_moderated', $table_name, array ('is_moderated' ) );

}

?>
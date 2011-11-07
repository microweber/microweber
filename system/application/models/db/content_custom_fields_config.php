<?php

$table_name = false;
$table_name = TABLE_PREFIX . "content_custom_fields_config";
$query = CI::db ()->query ( "show tables like '$table_name'" );
$query = $query->row_array ();
$query = (array_values ( $query ));

if ($query [0] != $table_name) {
	$sql = "CREATE TABLE " . $table_name . " (
		id int(150) NOT NULL auto_increment,
		UNIQUE KEY id (id)
		);
		ENGINE=MyISAM AUTO_INCREMENT=9999999   DEFAULT CHARSET=utf8
		";
	CI::db ()->query ( $sql );
}

$sql = "show tables like '$table_name'";
$query = CI::db ()->query ( $sql );
$query = $query->row_array ();
$query = (array_values ( $query ));
if ($query [0] == $table_name) {
	//$columns = $db->fetchAll("show columns from $table_name");
	/*
	$sql = "show columns from $table_name";
	$query = CI::db()->query ( $sql );
	$columns = $query->result_array ();

	$exisiting_fields = array ();
	foreach ( $columns as $fivesdraft ) {
		$fivesdraft = array_change_key_case ( $fivesdraft, CASE_LOWER );
		$exisiting_fields [strtolower ( $fivesdraft ['field'] )] = true;
	}
	*/
	
	$fields_to_add = array ();
	$fields_to_add [] = array ('page_id', 'varchar(1500) default NULL' );
	
	$fields_to_add [] = array ('post_id', 'varchar(1500) default NULL' );
	$fields_to_add [] = array ('module_id', 'varchar(1500) default NULL' );
	
	$fields_to_add [] = array ('name', 'varchar(1500) default NULL' );
	$fields_to_add [] = array ('param_group', 'varchar(1500) default NULL' );
	$fields_to_add [] = array ('help', 'TEXT default NULL' );
	//$fields_to_add [] = array ('updated_on', 'datetime default NULL' );
	//$fields_to_add [] = array ('created_on', 'datetime default NULL' );
	$fields_to_add [] = array ('to_table', 'varchar(1500) default NULL' );
	
	$fields_to_add [] = array ('content_type', 'varchar(1500) default NULL' );
	
	$fields_to_add [] = array ('type', 'TEXT default NULL' );
	$fields_to_add [] = array ('param', 'TEXT default NULL' );
	$fields_to_add [] = array ('param_values', 'TEXT default NULL' );
	$fields_to_add [] = array ('param_default', 'TEXT default NULL' );
	$fields_to_add [] = array ('field_order', 'int(11) default 999' );
	/*
	 
	$fields_to_add [] = array ('custom_field_for', 'varchar(150) default NULL' );
	$fields_to_add [] = array ('custom_field_type', 'varchar(150) default NULL' );
	$fields_to_add [] = array ('custom_field_default_value', 'TEXT default NULL' );
	*/
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
	
/*	$this->addIndex ( 'to_table', $table_name, array ('to_table' ) );
	$this->addIndex ( 'to_table_id', $table_name, array ('to_table_id' ) );
	$this->addIndex ( 'custom_field_name', $table_name, array ('custom_field_name' ), "FULLTEXT" );
	$this->addIndex ( 'custom_field_value', $table_name, array ('custom_field_value' ), "FULLTEXT" );*/

}

 
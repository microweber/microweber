<?php

$table_name = false;
$table_name = TABLE_PREFIX . "taxonomy";
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
	$fields_to_add = array ( );
	$fields_to_add [] = array ('taxonomy_type', 'varchar(1500) default NULL' );
	$fields_to_add [] = array ('taxonomy_value', 'varchar(1500) default NULL' );
	//$fields_to_add [] = array ('taxonomy_value2', 'varchar(1500) default NULL' );
	//$fields_to_add [] = array ('taxonomy_value3', 'varchar(1500) default NULL' );
	//$fields_to_add [] = array ('taxonomy_value', 'varchar(1500) default NULL' );
	$fields_to_add [] = array ('parent_id', 'int(11) default NULL' );
	$fields_to_add [] = array ('content_type', 'varchar(1500) default NULL' );
	//$fields_to_add [] = array ('content_id', 'int(11) default NULL' );
	$fields_to_add [] = array ('taxonomy_description', "TEXT default NULL" );
	$fields_to_add [] = array ('to_table', 'varchar(1500) default NULL' );
	$fields_to_add [] = array ('to_table_id', 'int(11) default NULL' );
	//$fields_to_add [] = array ('taxonomy_include_in_advanced_search', 'char(1) default "y"' );
	$fields_to_add [] = array ('content_body', "TEXT default NULL" );

	//$fields_to_add [] = array ('taxonomy_related_tags', "TEXT default NULL" );
	//$fields_to_add [] = array ('taxonomy_related_categories', "TEXT default NULL" );

	//$fields_to_add [] = array ('taxonomy_filename', 'varchar(1500) default NULL' );
	//$fields_to_add [] = array ('taxonomy_filename_exclusive', 'varchar(1500) default NULL' );
	//$fields_to_add [] = array ('taxonomy_filename_apply_to_child', 'char(1) default "n"' );
	//$fields_to_add [] = array ('taxonomy_params', "TEXT default NULL" );
	$fields_to_add [] = array ('taxonomy_silo_keywords', "TEXT default NULL" );
	$fields_to_add [] = array ('position', "int(11) default 999" );
	$fields_to_add [] = array ('updated_on', 'datetime default NULL' );
	$fields_to_add [] = array ('created_on', 'datetime default NULL' );

	$fields_to_add [] = array ('users_can_create_subcategories', 'char(1) default "n"' );
	$fields_to_add [] = array ('users_can_create_subcategories_user_level_required', 'int(11) default NULL' );

	$fields_to_add [] = array ('users_can_create_content', 'char(1) default "n"' );
	$fields_to_add [] = array ('users_can_create_content_user_level_required', 'int(11) default NULL' );

	$fields_to_add [] = array ('created_by', 'int(11) default NULL' );
	$fields_to_add [] = array ('edited_by', 'int(11) default NULL' );

	//$fields_to_add [] = array ('page_301_redirect_link', 'TEXT default NULL' );
	//$fields_to_add [] = array ('page_301_redirect_to_post_id', 'TEXT default NULL' );

	$fields_to_add [] = array ('taxonomy_content_type', 'varchar(1500) default NULL' );

	//$fields_to_add[] = array( 'group_to_table_id',   'int(11) default NULL');
	//$fields_to_add[] = array( 'is_active',   'int(1) default 1');


	$fields_to_add [] = array ('updated_on', 'datetime default NULL' );

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
	$this->set_db_tables($table_name, $fields_to_add );
	
	
	
	
	$this->addIndex('parent_id', $table_name, array('parent_id'));
	$this->addIndex('to_table', $table_name, array('to_table'));
 	$this->addIndex('content_type', $table_name, array('content_type'));
 
	
	
}
?>
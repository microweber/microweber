<?php

$table_name = false;
$table_name = TABLE_PREFIX . "users_log";
$query = CI::db()->query ( "show tables like '$table_name'" );
$query = $query->row_array ();
$query = (array_values ( $query ));

if ($query [0] != $table_name) {
	$sql = "CREATE TABLE " . $table_name . " (
        id int(11) NOT NULL auto_increment,
        UNIQUE KEY id (id)

        )  ENGINE=MyISAM DEFAULT CHARSET=utf8; ";
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
	$fields_to_add [] = array ('to_table_id', 'int(11) default NULL' );
	$fields_to_add [] = array ('user_id', 'int(11) default NULL' );
	$fields_to_add [] = array ('to_table', 'varchar(250) default NULL' );
	$fields_to_add [] = array ('rel_table', 'varchar(250) default NULL' );
	$fields_to_add [] = array ('rel_table_id', 'int(11) default NULL' );
	$fields_to_add [] = array ('session_id', 'varchar(250) default NULL' );
	$fields_to_add [] = array ('user_ip', 'varchar(40) default NULL' );
	$fields_to_add [] = array ('created_on', 'datetime default NULL' );
	$fields_to_add [] = array ('is_read', 'char(1) default "n"' );
	$fields_to_add [] = array ('notifications_parsed', 'char(1) default "n"' );
	$fields_to_add [] = array ('log_parsed', "ENUM('n', 'y')" );
	$fields_to_add [] = array ('created_by', 'int(11) default NULL' );
	$fields_to_add [] = array ('edited_by', 'int(11) default NULL' );

	$this->set_db_tables ( $table_name, $fields_to_add );
	
	$this->setEngine ( $table_name ); 
	
	$this->addIndex ( 'to_table_id', $table_name, array ('to_table_id' ) );
	$this->addIndex ( 'user_id', $table_name, array ('user_id' ) );
	$this->addIndex ( 'to_table', $table_name, array ('to_table' ) );
	$this->addIndex ( 'is_read', $table_name, array ('is_read' ) );
	$this->addIndex ( 'created_by', $table_name, array ('created_by' ) );
	$this->addIndex ( 'edited_by', $table_name, array ('edited_by' ) );
	$this->addIndex ( 'notifications_parsed', $table_name, array ('notifications_parsed' ) );

}
?>
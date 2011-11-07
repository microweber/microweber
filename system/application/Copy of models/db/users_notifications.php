<?php

$table_name = false;
$table_name = TABLE_PREFIX . "users_notifications";
$query = CI::db()->query ( "show tables like '$table_name'" );
$query = $query->row_array ();
$query = (array_values ( $query ));

if ($query [0] != $table_name) {
	$sql = "CREATE TABLE " . $table_name . " (
		id bigint(20) NOT NULL auto_increment,
		UNIQUE KEY id (id)
		);";
	CI::db()->query($sql);
}

$sql = "show tables like '$table_name'";
$query = CI::db()->query ( $sql );
$query = $query->row_array ();
$query = (array_values ( $query ));
if ($query [0] == $table_name) {

	$fields_to_add = array ();
	$fields_to_add [] = array ('from_user', 'int(10) default NULL' );
	$fields_to_add [] = array ('log_id', 'bigint(20) default NULL' );
	$fields_to_add [] = array ('to_user', 'int(10) default NULL');
	$fields_to_add [] = array ('type', 'varchar(50) default NULL');
	$fields_to_add [] = array ('subtype', 'varchar(50) default NULL');
	$fields_to_add [] = array ('subtype_value', 'varchar(50) default NULL');


	$fields_to_add [] = array ('message', 'TEXT default NULL');
	$fields_to_add [] = array ('to_table_id', 'int(10) default NULL');

	$fields_to_add [] = array ('is_read', 'char(1) default "n"');
	$fields_to_add [] = array ('to_table', 'varchar(20) default NULL');

	$fields_to_add [] = array ('updated_on', 'datetime default NULL' );
	$fields_to_add [] = array ('created_on', 'datetime default NULL' );
	$fields_to_add [] = array ('created_by', 'int(11) default NULL' );
	$fields_to_add [] = array ('edited_by', 'int(11) default NULL' );

	$this->set_db_tables ( $table_name, $fields_to_add );

}

$this->setEngine($table_name);

CI::db()->query(
	"ALTER TABLE {$table_name} CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

// foreign keys
$this->addForeignKey("FK_{$table_name}_1", $table_name, array('from_user'), TABLE_PREFIX.'users', array('id'), $aOptions = array('delete' => 'CASCADE'));
$this->addForeignKey("FK_{$table_name}_2", $table_name, array('to_user'), TABLE_PREFIX.'users', array('id'), $aOptions = array('delete' => 'CASCADE'));

?>
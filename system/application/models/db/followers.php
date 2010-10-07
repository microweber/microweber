<?php

$table_name = false;
$table_name = TABLE_PREFIX . "followers";
$query = $this->db->query ( "show tables like '$table_name'" );
$query = $query->row_array ();
$query = (array_values ( $query ));

if ($query [0] != $table_name) {
	$sql = "CREATE TABLE " . $table_name . " (
		id bigint(20) NOT NULL auto_increment,
		UNIQUE KEY id (id)
		);";
	$this->db->query ( $sql );
}

$sql = "show tables like '$table_name'";
$query = $this->db->query ( $sql );
$query = $query->row_array ();
$query = (array_values ( $query ));
if ($query [0] == $table_name) {

	$fields_to_add = array ();
	$fields_to_add [] = array ('follower_id', 'int(10) NOT NULL' );
	$fields_to_add [] = array ('user_id', 'int(10) NOT NULL' );
	$fields_to_add [] = array ('is_special', 'char(1) default "n"' );

	$fields_to_add [] = array ('updated_on', 'datetime default NULL' );
	$fields_to_add [] = array ('created_on', 'datetime default NULL' );
	$fields_to_add [] = array ('created_by', 'int(11) default NULL' );
	$fields_to_add [] = array ('edited_by', 'int(11) default NULL' );

	$this->set_db_tables ( $table_name, $fields_to_add );

}

$this->setEngine ( $table_name );

$this->db->query ( "ALTER TABLE {$table_name} CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;" );

// foreign keys
//$this->addForeignKey ( 'FK_firecms_followers_1', $table_name, array ('follower_id' ), TABLE_PREFIX . 'users', array ('id' ), array ('delete' => 'CASCADE' ) );
//$this->addForeignKey ( 'FK_firecms_followers_2', $table_name, array ('user_id' ), TABLE_PREFIX . 'users', array ('id' ), array ('delete' => 'CASCADE' ) );

?>
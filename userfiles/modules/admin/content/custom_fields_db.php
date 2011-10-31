<?php






$table_name = false;
$table_name = TABLE_PREFIX . "mw_module_cf_edit";
$query = CI::db ()->query ( "show tables like '$table_name'" );


$query = $query->row_array ();
$query = (array_values ( $query ));

if ($query [0] != $table_name) {
	$sql = "CREATE TABLE " . $table_name . " (
		id int(150) NOT NULL auto_increment,
		UNIQUE KEY id (id)
		);
		ENGINE=MyISAM  DEFAULT CHARSET=utf8
		";
		//p($sql);
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
	$fields_to_add [] = array ('cf_id', 'int(11) default NULL' );
	 
	 
	$this->set_db_tables ( $table_name, $fields_to_add );
	
/*	$this->addIndex ( 'to_table', $table_name, array ('to_table' ) );
	$this->addIndex ( 'to_table_id', $table_name, array ('to_table_id' ) );
	$this->addIndex ( 'custom_field_name', $table_name, array ('custom_field_name' ), "FULLTEXT" );
	$this->addIndex ( 'custom_field_value', $table_name, array ('custom_field_value' ), "FULLTEXT" );*/

}

 
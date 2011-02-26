<?php

$table_name = false;
$table_name = TABLE_PREFIX . "cart";
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
	
	$fields_to_add = array ();
	$fields_to_add [] = array ('to_table', 'varchar(1500) default NULL' );
	$fields_to_add [] = array ('to_table_id', 'int(11) default NULL' );
	$fields_to_add [] = array ('updated_on', 'datetime default NULL' );
	$fields_to_add [] = array ('created_on', 'datetime default NULL' );
	$fields_to_add [] = array ('item_name', 'TEXT default NULL' );
	$fields_to_add [] = array ('price', 'TEXT default NULL' );
	$fields_to_add [] = array ('currency', 'TEXT default NULL' );
	$fields_to_add [] = array ('weight', 'TEXT default NULL' );
	$fields_to_add [] = array ('height', 'TEXT default NULL' );
	$fields_to_add [] = array ('length', 'TEXT default NULL' );
	$fields_to_add [] = array ('width', 'TEXT default NULL' );
	$fields_to_add [] = array ('qty', 'TEXT default NULL' );
	$fields_to_add [] = array ('other_info', 'TEXT default NULL' );
	$fields_to_add [] = array ('sid', 'TEXT default NULL' );
	$fields_to_add [] = array ('sku', 'TEXT default NULL' );
	$fields_to_add [] = array ('size', 'TEXT default NULL' );
	$fields_to_add [] = array ('colors', 'TEXT default NULL' );
	$fields_to_add [] = array ('order_completed', 'char(1) default "n"' );
	$fields_to_add [] = array ('order_id', 'TEXT default NULL' );
$fields_to_add [] = array ('added_shipping_price', 'varchar(50) default NULL' );
$fields_to_add [] = array ('skip_promo_code', 'char(1) default "n"' );
	$fields_to_add [] = array ('to_table', 'varchar(1500) default NULL' );
	




	$this->set_db_tables($table_name, $fields_to_add );

}

?>
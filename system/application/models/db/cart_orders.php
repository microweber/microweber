<?php

$table_name = false;
$table_name = TABLE_PREFIX . "cart_orders";
$query = CI::db ()->query ( "show tables like '$table_name'" );
$query = $query->row_array ();
$query = (array_values ( $query ));

if ($query [0] != $table_name) {
	$sql = "CREATE TABLE " . $table_name . " (
		id int(11) NOT NULL auto_increment,
		UNIQUE KEY id (id)
		);";
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
	$fields_to_add [] = array ('clientid', 'int(11) default NULL' );
	//$fields_to_add [] = array ('merchantaccountid', 'int(11) default NULL' );
	

	$fields_to_add [] = array ('customercode', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('sid', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('transactionid', 'VARCHAR(255) default NULL' );
	
	$fields_to_add [] = array ('amount', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('order_id', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('cardholdernumber', 'VARCHAR(50) default NULL' );
	$fields_to_add [] = array ('expiresmonth', 'int(11) default 1' );
	$fields_to_add [] = array ('expiresyear', 'int(11) default 2000' );
	$fields_to_add [] = array ('cvv2', 'int(11) default NULL' );
	$fields_to_add [] = array ('bname', 'VARCHAR(30) default NULL' );
	$fields_to_add [] = array ('bemailaddress', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('baddress1', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('bcity', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('bstate', 'VARCHAR(3) default NULL' );
	$fields_to_add [] = array ('bzipcode', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('country', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('bphone', 'VARCHAR(40) default NULL' );
	$fields_to_add [] = array ('shipping_total_charges', 'VARCHAR(255) default NULL' );
	
	$fields_to_add [] = array ('updated_on', 'datetime default NULL' );
	$fields_to_add [] = array ('created_on', 'datetime default NULL' );
	$fields_to_add [] = array ('promo_code', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('tracking_number', 'TEXT default NULL' );
	$fields_to_add [] = array ('shipping_service', 'VARCHAR(4) default NULL' );
	
	$fields_to_add [] = array ('shipping', 'VARCHAR(255) default NULL' );
	
	$fields_to_add [] = array ('currency_code', 'VARCHAR(255) default NULL' );
	
	$fields_to_add [] = array ('promo_code', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('last_name', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('names', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('first_name', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('email', 'VARCHAR(255) default NULL' );
	
	$fields_to_add [] = array ('country', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('city', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('state', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('zip', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('phone', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('address', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('address2', 'VARCHAR(255) default NULL' );
	
	$fields_to_add [] = array ('url', 'varchar(1500) default NULL' );
	$fields_to_add [] = array ('user_ip', 'varchar(255) default NULL' );
	
	$fields_to_add [] = array ('to_table', 'varchar(1500) default NULL' );
	$fields_to_add [] = array ('to_table_id', 'int(11) default NULL' );
	$fields_to_add [] = array ('created_by', 'int(11) default NULL' );
	$fields_to_add [] = array ('edited_by', 'int(11) default NULL' );
	$fields_to_add [] = array ('session_id', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('is_paid', 'char(1) default "n"' );
	
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

}

?>
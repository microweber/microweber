<?php

$table_name = false;
$table_name = TABLE_PREFIX . "cart_orders";
$query = $this->db->query ( "show tables like '$table_name'" );
$query = $query->row_array ();
$query = (array_values ( $query ));

if ($query [0] != $table_name) {
	$sql = "CREATE TABLE " . $table_name . " (
		id int(11) NOT NULL auto_increment,
		UNIQUE KEY id (id)
		);";
	$this->db->query ( $sql );
}

$sql = "show tables like '$table_name'";
$query = $this->db->query ( $sql );
$query = $query->row_array ();
$query = (array_values ( $query ));
if ($query [0] == $table_name) {
	//$columns = $db->fetchAll("show columns from $table_name");
	/*
	$sql = "show columns from $table_name";
	$query = $this->db->query ( $sql );
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
	
	$fields_to_add [] = array ('sname', 'VARCHAR(120) default NULL' );
	$fields_to_add [] = array ('scompany', 'VARCHAR(120) default NULL' );
	$fields_to_add [] = array ('saddress1', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('saddress2', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('scity', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('sstate', 'VARCHAR(3) default NULL' );
	$fields_to_add [] = array ('szipcode', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('scountry', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('sphone', 'VARCHAR(40) default NULL' );
	$fields_to_add [] = array ('semailaddress', 'VARCHAR(255) default NULL' );
	
	$fields_to_add [] = array ('updated_on', 'datetime default NULL' );
	$fields_to_add [] = array ('created_on', 'datetime default NULL' );
	$fields_to_add [] = array ('promo_code', 'VARCHAR(255) default NULL' );
	$fields_to_add [] = array ('tracking_number', 'TEXT default NULL' );
	$fields_to_add [] = array ('shipping_service', 'VARCHAR(4) default NULL' );
	
	
	/*
	foreach ( $fields_to_add as $the_field ) {
		$sql = false;
		$the_field [0] = strtolower ( $the_field [0] );
		if ($exisiting_fields [$the_field [0]] != true) {
			$sql = "alter table $table_name add column {$the_field[0]} {$the_field[1]} ";
			$this->db->query ( $sql );
		} else {
			$sql = "alter table $table_name modify {$the_field[0]} {$the_field[1]} ";
			$this->db->query ( $sql );
		}
	
	}
	*/
	$this->set_db_tables($table_name, $fields_to_add );

}

?>
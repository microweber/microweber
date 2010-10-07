<?php

$table = TABLE_PREFIX . 'affiliate_links';
$query = $this->db->query("SHOW TABLES LIKE '$table'");
$query = $query->row_array();
$query = (array_values($query));

if($query[0] != $table) {
	$sql = "
			CREATE TABLE $table (
			  id         INT(11) NOT NULL AUTO_INCREMENT,
              url_key    VARBINARY(255) NOT NULL,
              url        VARCHAR(255) NOT NULL,
              created_on INT(10) UNSIGNED NOT NULL,
              PRIMARY KEY (id),
			  UNIQUE KEY key(url_key)
		    );
	";
	
	$this->db->query($sql);
}

$sql = "SHOW TABLES LIKE '$table'";
$query = $this->db->query($sql);
$query = $query->row_array();
$query = (array_values($query));

if($query[0] == $table) {

	$fields = array();
	$fields[] = array('id', 		'INT(11) NOT NULL AUTO_INCREMENT');
	$fields[] = array('url_key',    'VARBINARY(255) NOT NULL');
	$fields[] = array('url',        'VARCHAR(255) NOT NULL');
	$fields[] = array('created_on', 'INT(10) UNSIGNED NOT NULL');

	$this->set_db_tables ($table, $fields);
}

?>

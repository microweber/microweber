<?php

$table_name = false;
$table_name = TABLE_PREFIX . "users";
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
	
	$fields_to_add = array ();
	$fields_to_add [] = array ('username', 'varchar(250) default NULL' );
	$fields_to_add [] = array ('password', 'varchar(250) default NULL' );
	$fields_to_add [] = array ('email', 'varchar(250) default NULL' );
	//$fields_to_add [] = array ('session_id', 'varchar(250) default NULL' );
	//$fields_to_add [] = array ('lastlogin_ip', 'varchar(250) default NULL' );
	//$fields_to_add [] = array ('user_level', "int(11) default 10" );
	

	$fields_to_add [] = array ('is_active', 'char(1) default "y"' );
	//$fields_to_add [] = array ('is_popular', 'char(1) default "n"' );
	

	$fields_to_add [] = array ('is_verified', 'char(1) default "n"' );
	
	$fields_to_add [] = array ('is_admin', 'char(1) default "n"' );
	$fields_to_add [] = array ('updated_on', 'datetime default NULL' );
	$fields_to_add [] = array ('created_on', 'datetime default NULL' );
	$fields_to_add [] = array ('expires_on', 'datetime default NULL' );
	//	$fields_to_add [] = array ('last_activity', 'datetime default NULL' );
	

	$fields_to_add [] = array ('created_by', 'int(11) default NULL' );
	$fields_to_add [] = array ('edited_by', 'int(11) default NULL' );
	$fields_to_add [] = array ('parent_id', 'int(11) default NULL' );
	
	$fields_to_add [] = array ('first_name', 'varchar(250) default NULL' );
	$fields_to_add [] = array ('last_name', 'varchar(250) default NULL' );
	$fields_to_add [] = array ('fb_uid', 'varchar(50) default NULL' );
	
	$fields_to_add [] = array ('subscr_id', 'varchar(50) default NULL' ); //subscription id
	

	$fields_to_add [] = array ('user_information', "LONGTEXT default NULL" );
	//$fields_to_add [] = array ('user_status', "TEXT default NULL" );
	//$fields_to_add [] = array ('user_image', "TEXT default NULL" );
	//$fields_to_add [] = array ('user_image', "TEXT default NULL" );
	//	$fields_to_add [] = array ('user_blog', 'varchar(250) default NULL' );
	//$fields_to_add [] = array ('user_website', 'varchar(250) default NULL' );
	

	//$fields_to_add [] = array ('chat_skype', 'varchar(250) default NULL' );
	//$fields_to_add [] = array ('chat_googletalk', 'varchar(250) default NULL' );
	//$fields_to_add [] = array ('chat_icq', 'varchar(250) default NULL' );
	//$fields_to_add [] = array ('chat_msn', 'varchar(250) default NULL' );
	

	//$fields_to_add [] = array ('social_facebook', 'varchar(250) default NULL' );
	//$fields_to_add [] = array ('social_myspace', 'varchar(250) default NULL' );
	//$fields_to_add [] = array ('social_linkedin', 'varchar(250) default NULL' );
	//$fields_to_add [] = array ('social_twitter', 'varchar(250) default NULL' );
	//$fields_to_add [] = array ('social_youtube', 'varchar(250) default NULL' );
	/*
	$fields_to_add [] = array ('user_company', 'TEXT default NULL' );
	$fields_to_add [] = array ('user_city', 'TEXT default NULL' );
	$fields_to_add [] = array ('user_state', 'TEXT default NULL' );
	$fields_to_add [] = array ('user_zip', 'TEXT default NULL' );
	$fields_to_add [] = array ('user_address', 'TEXT default NULL' );
	$fields_to_add [] = array ('user_phone', 'TEXT default NULL' );
	*/
	//$fields_to_add [] = array ('payment_cheque_name', 'TEXT default NULL' );
	//$fields_to_add [] = array ('payment_paypal', 'TEXT default NULL' );
	

	//$fields_to_add [] = array ('parent_affil', "INTEGER(11) UNSIGNED NOT NULL DEFAULT '0' " );
	//$fields_to_add [] = array ('payout', "INTEGER(11) UNSIGNED NOT NULL DEFAULT '0'" );
	

	///$fields_to_add [] = array ('birthday', " DATE DEFAULT NULL " );
	//$fields_to_add [] = array ('addr1', " TEXT default NULL " );
	//$fields_to_add [] = array ('addr2', " TEXT default NULL " );
	//$fields_to_add [] = array ('city', " VARCHAR(64)  DEFAULT NULL " );
	//$fields_to_add [] = array ('zip', " VARCHAR(16)  DEFAULT NULL " );
	//$fields_to_add [] = array ('country', " VARCHAR(16)  DEFAULT NULL " );
	//$fields_to_add [] = array ('phone', " VARCHAR(64)  DEFAULT NULL " );
	//$fields_to_add [] = array ('state', " VARCHAR(64)  DEFAULT NULL " );
	

	//$fields_to_add [] = array ('fax', " VARCHAR(64)  DEFAULT NULL " );
	//$fields_to_add [] = array ('ssn', " VARCHAR(64)  DEFAULT NULL " );
	//$fields_to_add [] = array ('website', " VARCHAR(255)  DEFAULT NULL " );
	//$fields_to_add [] = array ('blog', 'varchar(250) default NULL' );
	//$fields_to_add [] = array ('order_id', 'varchar(250) default NULL' );
	//$fields_to_add [] = array ('video_url', 'varchar(250) default NULL' );
	//$fields_to_add [] = array ('video_embed', 'TEXT default NULL' );
	

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
	
	$this->setEngine ( $table_name );
	
	$this->addIndex ( 'username', $table_name, array ('username' ) );
	$this->addIndex ( 'password', $table_name, array ('password' ) );
	$this->addIndex ( 'email', $table_name, array ('email' ) );
	$this->addIndex ( 'created_on', $table_name, array ('created_on' ) );
	$this->addIndex ( 'first_name', $table_name, array ('first_name' ), 'FULLTEXT' );
	$this->addIndex ( 'last_name', $table_name, array ('last_name' ), 'FULLTEXT' );
	$this->addIndex ( 'user_information', $table_name, array ('user_information' ), 'FULLTEXT' );
	$this->addIndex ( 'fb_uid', $table_name, array ('fb_uid' ), 'FULLTEXT' );

}
?>
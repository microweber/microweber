<?php

$table_name = false;
$table_name = TABLE_PREFIX . "content";
$query = CI::db()->query ( "show tables like '$table_name'" );
$query = $query->row_array ();
$query = (array_values ( $query ));

if ($query [0] != $table_name) {
	$sql = "CREATE TABLE " . $table_name . " (
		id int(11) NOT NULL auto_increment,
		UNIQUE KEY id (id)
		);
		ENGINE=MyISAM   DEFAULT CHARSET=utf8
		";
	CI::db()->query ( $sql );
}

$sql = "show tables like '$table_name'";
$query = CI::db()->query ( $sql );
$query = $query->row_array ();
$query = (array_values ( $query ));
if ($query [0] == $table_name) {
	//$columns = $db->fetchAll("show columns from $table_name");
	

	//p($exisiting_fields,1);
	$fields_to_add = array ();
	$fields_to_add [] = array ('content_type', 'varchar(150) default NULL' );
	$fields_to_add [] = array ('content_subtype', 'varchar(150) default "none"' );
	$fields_to_add [] = array ('content_subtype_value', 'varchar(150) default "none"' );
	
	$fields_to_add [] = array ('content_layout_file', 'varchar(150) default NULL' );
	$fields_to_add [] = array ('content_layout_name', 'varchar(50) default NULL' );
	$fields_to_add [] = array ('content_layout_style', 'varchar(50) default NULL' );
	
	$fields_to_add [] = array ('content_url', 'varchar(150) default NULL' );
	//$fields_to_add [] = array ('content_url_md5', 'varchar(33) default NULL' );
	

	$fields_to_add [] = array ('content_filename', 'varchar(150) default NULL' );
	$fields_to_add [] = array ('content_filename_sync_with_editor', 'char(1) default "n"' );
	//$fields_to_add [] = array ('model_group', 'varchar(150) default NULL' );
	$fields_to_add [] = array ('content_parent', 'int(11) default 0' );
	$fields_to_add [] = array ('content_title', 'varchar(150) default NULL' );
	$fields_to_add [] = array ('content_meta_title', 'varchar(600) default NULL' );
	$fields_to_add [] = array ('content_meta_description', 'TEXT default NULL' );
	$fields_to_add [] = array ('content_meta_keywords', 'TEXT default NULL' );
	$fields_to_add [] = array ('content_meta_other_code', 'TEXT default NULL' );
	
	$fields_to_add [] = array ('content_body', "LONGTEXT default NULL" );
	$fields_to_add [] = array ('content_body_filename', "varchar(150) default NULL" );
	$fields_to_add [] = array ('active_site_template', "varchar(150) default NULL" );
	
	
	//$fields_to_add [] = array ('content_body2', "LONGTEXT default NULL" );
	//$fields_to_add [] = array ('content_section_name', "LONGTEXT default NULL" );
	//$fields_to_add [] = array ('content_section_name2', "LONGTEXT default NULL" );
	

	$fields_to_add [] = array ('content_description', "TEXT default NULL" );
	
	$fields_to_add [] = array ('is_active', 'char(1) default "y"' );
	$fields_to_add [] = array ('is_home', 'char(1) default "n"' );
	$fields_to_add [] = array ('is_featured', 'char(1) default "n"' );
	$fields_to_add [] = array ('is_pinged', 'char(1) default "n"' );
	$fields_to_add [] = array ('comments_enabled', 'char(1) default "y"' );
	$fields_to_add [] = array ('updated_on', 'datetime default NULL' );
	$fields_to_add [] = array ('created_on', 'datetime default NULL' );
	//$fields_to_add [] = array ('tag1', 'TEXT default NULL' );
	//$fields_to_add [] = array ('tag2', 'TEXT default NULL' );
	//$fields_to_add [] = array ('test123', 'TEXT default NULL' );
	//$fields_to_add [] = array ('is_from_rss', 'char(1) default "n"' );
	$fields_to_add [] = array ('visible_on_frontend', 'char(1) default "y"' );
	//$fields_to_add [] = array ('rss_feed_id', 'varchar(150) default NULL' );
	//$fields_to_add [] = array ('page_301_redirect_link', 'TEXT default NULL' );
	//$fields_to_add [] = array ('page_301_redirect_to_post_id', 'TEXT default NULL' );
	$fields_to_add [] = array ('original_link', 'TEXT default NULL' );
	//$fields_to_add [] = array ('original_link_no_follow', 'char(1) default "n"' );
	//$fields_to_add [] = array ('original_link_last_remap', 'datetime default NULL' );
	

	//$fields_to_add [] = array ('original_link_include_in_advanced_search', 'char(1) default "y"' );
	//$fields_to_add [] = array ('content_unique_id', 'varchar(150) default NULL' );
	

	$fields_to_add [] = array ('require_login', "ENUM('n', 'y') default 'n'" );
	$fields_to_add [] = array ('created_by', 'int(11) default NULL' );
	$fields_to_add [] = array ('edited_by', 'int(11) default NULL' );
	$fields_to_add [] = array ('is_special', 'char(1) default "n"' );
	
	$this->set_db_tables ( $table_name, $fields_to_add );
	
	//add fulltext search
	//ALTER TABLE articles ADD FULLTEXT(body, title);
	//$sql = "alter table $table_name add FULLTEXT (content_url, content_title, content_body)  ";
	//CI::db()->query ( $sql );
	

	$this->addIndex ( 'content_type', $table_name, array ('content_type' ) );
	$this->addIndex ( 'content_subtype', $table_name, array ('content_subtype' ) );
	$this->addIndex ( 'content_title', $table_name, array ('content_subtype' ), "FULLTEXT" );
	$this->addIndex ( 'content_body', $table_name, array ('content_body' ), "FULLTEXT" );
	$this->addIndex ( 'content_description', $table_name, array ('content_description' ), "FULLTEXT" );
	$this->addIndex ( 'content_url', $table_name, array ('content_url' ), "FULLTEXT" );
	
	$this->addIndex ( 'created_by', $table_name, array ('created_by' ) );
	$this->addIndex ( 'edited_by', $table_name, array ('edited_by' ) );

}

?>
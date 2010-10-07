<?php

$table_name = false;
$table_name = TABLE_PREFIX . "content";
$query = $this->db->query ( "show tables like '$table_name'" );
$query = $query->row_array ();
$query = (array_values ( $query ));

if ($query [0] != $table_name) {
	$sql = "CREATE TABLE " . $table_name . " (
		id int(11) NOT NULL auto_increment,
		UNIQUE KEY id (id)
		);
		ENGINE=MyISAM   DEFAULT CHARSET=utf8
		";
	$this->db->query ( $sql );
}


 

$sql = "show tables like '$table_name'";
$query = $this->db->query ( $sql );
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
	$fields_to_add [] = array ('content_url_md5', 'varchar(33) default NULL' );

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

	$fields_to_add [] = array ('content_body2', "LONGTEXT default NULL" );
	$fields_to_add [] = array ('content_section_name', "LONGTEXT default NULL" );
	$fields_to_add [] = array ('content_section_name2', "LONGTEXT default NULL" );

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
	$fields_to_add [] = array ('is_from_rss', 'char(1) default "n"' );
	$fields_to_add [] = array ('visible_on_frontend', 'char(1) default "y"' );
	$fields_to_add [] = array ('rss_feed_id', 'varchar(150) default NULL' );
	$fields_to_add [] = array ('page_301_redirect_link', 'TEXT default NULL' );
	$fields_to_add [] = array ('page_301_redirect_to_post_id', 'TEXT default NULL' );
	$fields_to_add [] = array ('original_link', 'TEXT default NULL' );
	$fields_to_add [] = array ('original_link_no_follow', 'char(1) default "n"' );
	$fields_to_add [] = array ('original_link_last_remap', 'datetime default NULL' );

	//$fields_to_add [] = array ('original_link_include_in_advanced_search', 'char(1) default "y"' );
	$fields_to_add [] = array ('content_unique_id', 'varchar(150) default NULL' );

	$fields_to_add [] = array ('created_by', 'int(11) default NULL' );
	$fields_to_add [] = array ('edited_by', 'int(11) default NULL' );

	//$fields_to_add [] = array ('the_old_id', 'int(11) default NULL' );

	//$fields_to_add [] = array ('trainings', 'char(1) default "n"' );
	//$fields_to_add [] = array ('products', 'char(1) default "n"' );
	//$fields_to_add [] = array ('services', 'char(1) default "n"' );


	$fields_to_add [] = array ('is_special', 'char(1) default "n"' );
	//$fields_to_add [] = array ('is_pinged', 'char(1) default "n"' );
	//$fields_to_add [] = array ('is_pinged', 'char(1) default "n"' );


	//$fields_to_add [] = array ('custom_field_1', 'TEXT default NULL' );
	//$fields_to_add [] = array ('custom_field_2', 'TEXT default NULL' );
	//$fields_to_add [] = array ('custom_field_3', 'TEXT default NULL' );
	//$fields_to_add [] = array ('custom_field_4', 'TEXT default NULL' );
	//$fields_to_add [] = array ('custom_field_5', 'TEXT default NULL' );
	//$fields_to_add [] = array ('custom_field_6', 'TEXT default NULL' );
	//$fields_to_add [] = array ('custom_field_7', 'TEXT default NULL' );
	//$fields_to_add [] = array ('custom_field_8', 'TEXT default NULL' );
	//$fields_to_add [] = array ('custom_field_9', 'TEXT default NULL' );
	//$fields_to_add [] = array ('custom_field_10', 'TEXT default NULL' );
	//$fields_to_add [] = array ('niki', 'VARCHAR(10) default NULL' );


	$this->set_db_tables ( $table_name, $fields_to_add );

	//add fulltext search
	//ALTER TABLE articles ADD FULLTEXT(body, title);
	//$sql = "alter table $table_name add FULLTEXT (content_url, content_title, content_body)  ";
	//$this->db->query ( $sql );

}

?>
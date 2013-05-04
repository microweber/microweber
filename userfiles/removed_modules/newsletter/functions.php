<?php


/**
 *
 * Newsletter module api
 *
 * @package		modules
 * @subpackage		newsletter
 * @since		Version 0.1
 */

// ------------------------------------------------------------------------
action_hook('mw_db_init', 'mw_newsletter_module_init_db');
if (!defined("MODULE_DB_USERS_ONLINE")) {
    define('MODULE_DB_NEWSLETTER', MW_TABLE_PREFIX . 'newsletter');
}
function mw_newsletter_module_init_db() {
 	$function_cache_id = false;

	$args = func_get_args();

	foreach ($args as $k => $v) {

		$function_cache_id = $function_cache_id . serialize($k) . serialize($v);
	}

	$function_cache_id = __FUNCTION__ . crc32($function_cache_id);

	$cache_content = cache_get_content($function_cache_id, 'db');

	if (($cache_content) != false) {

		return $cache_content;
	}
 
 
  $active = url_param('view');
  $cls = '';
  if($active == 'settings'){
	   $cls = ' class="active" ';
  }
   $table_name = MODULE_DB_NEWSLETTER;
  
  
  $fields_to_add = array ();
	$fields_to_add [] = array ('title', 'TEXT default NULL' );
	$fields_to_add [] = array ('is_active', "char(1) default 'y'" );
	 set_db_table ( $table_name, $fields_to_add );
	
	
	 db_add_table_index ( 'title', $table_name, array ('title' ), "FULLTEXT" );

	
	
	
	
	/*$fields_to_add [] = array ('content_subtype', 'varchar(150) default "none"' );
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
 
	
	$fields_to_add [] = array ('is_active', 'char(1) default "y"' );
	$fields_to_add [] = array ('is_home', 'char(1) default "n"' );
	$fields_to_add [] = array ('is_featured', 'char(1) default "n"' );
	$fields_to_add [] = array ('is_pinged', 'char(1) default "n"' );
	$fields_to_add [] = array ('comments_enabled', 'char(1) default "y"' );
	$fields_to_add [] = array ('updated_on', 'datetime default NULL' );
	$fields_to_add [] = array ('created_on', 'datetime default NULL' );
	
	$fields_to_add [] = array ('expires_on', 'datetime default NULL' );
  
	$fields_to_add [] = array ('visible_on_frontend', 'char(1) default "y"' );
 
	$fields_to_add [] = array ('original_link', 'TEXT default NULL' );
 

	$fields_to_add [] = array ('require_login', "ENUM('n', 'y') default 'n'" );
	$fields_to_add [] = array ('created_by', 'int(11) default NULL' );
	$fields_to_add [] = array ('edited_by', 'int(11) default NULL' );
	$fields_to_add [] = array ('is_special', 'char(1) default "n"' );*/
	 
	
	
	//add fulltext search
	//ALTER TABLE articles ADD FULLTEXT(body, title);
	//$sql = "alter table $table_name add FULLTEXT (content_url, content_title, content_body)  ";
	//$this->db->query ( $sql );
	
/*
	db_add_table_index ( 'content_type', $table_name, array ('content_type' ) );
	db_add_table_index( 'content_subtype', $table_name, array ('content_subtype' ) );
	db_add_table_index ( 'content_title', $table_name, array ('content_subtype' ), "FULLTEXT" );
	db_add_table_index ( 'content_body', $table_name, array ('content_body' ), "FULLTEXT" );
	db_add_table_index ( 'content_description', $table_name, array ('content_description' ), "FULLTEXT" );
	db_add_table_index ( 'content_url', $table_name, array ('content_url' ), "FULLTEXT" );
	
	db_add_table_index ( 'created_by', $table_name, array ('created_by' ) );
	db_add_table_index( 'edited_by', $table_name, array ('edited_by' ) );
  
  
  */
  
  
  cache_save(true, $function_cache_id, $cache_group = 'db');
	// $fields = (array_change_key_case ( $fields, CASE_LOWER ));
	return true;
  
  
	//print '<li'.$cls.'><a href="'.admin_url().'view:settings">newsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl etenewsl eter</a></li>';
}

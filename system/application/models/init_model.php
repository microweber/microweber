<?php

define ( 'FIRECMS_DB_VERSION', 2 );
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
	
//l setcookie  ( string $name  [, string $value  [, int $expire=0  [, string $path  [, string $domain  [, bool $secure=false  [, bool $httponly=false  ]]]]]] )
//setcookie ( 'fckEditor_filesurl', base64_encode ( $media_url ), time () + 36000, '/' );
$media_url = base_url ();
$media_url = $media_url . '/' . USERFILES_DIRNAME . '/media/';
$media_url = reduce_double_slashes ( $media_url );

/*if ($_COOKIE ['fckEditor_filespath'] == false) {
	setcookie ( 'fckEditor_filespath', base64_encode ( MEDIAFILES ), time () + 36000, '/' );
}
if ($_COOKIE ['fckEditor_filesurl'] == false) {
	setcookie ( 'fckEditor_filesurl', base64_encode ( $media_url ), time () + 36000, '/' );
}

if ($_COOKIE ['site_url'] != SITEURL) {
	setcookie ( 'site_url', SITEURL, time () + 36000, '/' );
}

if ($_COOKIE ['root_path'] != ROOTPATH) {
	setcookie ( 'root_path', ROOTPATH, time () + 36000, '/' );
}

if ($_COOKIE ['index_path'] != FCPATH) {
	setcookie ( 'index_path', FCPATH, time () + 36000, '/' );
}

$tbpath = static_url () . 'js/tiny_mce/plugins/tinybrowser/';
if ($_COOKIE ['tiny_browser_path'] != $tbpath) {
	setcookie ( 'tiny_browser_path', $tbpath, time () + 36000, '/' );
}*/

class Init_model extends Model {
	
	function __construct() {
		parent::Model ();
		//$this->db_setup ();
	}
	
	function db_setup($plugin_dir = false) {
		if (is_dir ( $plugin_dir ) == true) {
			$dir = $plugin_dir . '/db/';
			$dir2 = $plugin_dir . '/db/tmp/';
		} else {
			$dir = APPPATH . '/models/db/';
			$dir2 = CACHEDIR_ROOT . '/models/db/tmp/';
		}
		
		$dir2 = CACHEDIR_ROOT . '/db_tmp/';
		
		if (is_dir ( $dir ) == false) {
			@mkdir_recursive ( $dir );
		}
		if (is_dir ( $dir2 ) == false) {
			@mkdir_recursive ( $dir2 );
		}
		
		/*if (is_writable ( $dir ) == false) {
			@chmod ( $dir, 0777 );

		}*/
		
		/*if (is_writable ( $dir2 ) == false) {
			@chmod ( $dir2, 0777 );
		}*/
		
		$cache_file = $dir2 . 'index.php';
		if (is_file ( $cache_file ) == true) {
			
			return true;
		}
		
		if (is_dir ( $dir ) == true) {
			
			touch ( $cache_file );
			$handle = (@opendir ( $dir ));
			//require_once ($dir . 'options.php');
			while ( false !== ($file = readdir ( $handle )) ) {
				if (stristr ( $file, 'disabled' ) == false) {
					if (stristr ( $file, 'php' ) == TRUE) {
						$checksum = @md5_file ( $dir . $file );
						$src_filename = @md5 ( $file );
						$inc = false;
						$dir_and_file = reduce_double_slashes ( $dir2 . $src_filename );
						if (file_exists ( $dir_and_file ) == false) {
							$inc = true;
							@touch ( $dir_and_file );
							$fp = @fopen ( $dir_and_file, 'w' );
							@fwrite ( $fp, $checksum );
							@fclose ( $fp );
						} else {
							$content = file_get_contents ( $dir2 . $src_filename );
							//var_dump($checksum , $content);
							if ($checksum == $content) {
								$inc = false;
							} else {
								$inc = true;
								$fp = @fopen ( $dir2 . $src_filename, 'w' );
								@fwrite ( $fp, $checksum );
								@fclose ( $fp );
							}
						}
						if ($inc == true) {
							//print "inc $dir.$file      ";
							require_once ($dir . $file);
						}
					}
				}
			}
			
			@closedir ( $handle );
		
		}
	
	} // end db_setup
	

	function db_setup_from_file($path_to) {
		
		$dir = APPPATH . '/models/db/';
		$dir2 = CACHEDIR_ROOT . '/models/db/tmp/';
		
		$dir2 = CACHEDIR_ROOT . '/db_tmp/';
		
		if (is_dir ( $dir ) == false) {
			@mkdir_recursive ( $dir );
		}
		if (is_dir ( $dir2 ) == false) {
			@mkdir_recursive ( $dir2 );
		}
		
		/*if (is_writable ( $dir ) == false) {
			@chmod ( $dir, 0777 );

		}*/
		
		/*if (is_writable ( $dir2 ) == false) {
			@chmod ( $dir2, 0777 );
		}*/
		
		$cache_file = $dir2 . 'db_setup_from_file_cache' . md5 ( $path_to );
		if (is_file ( $cache_file ) == true) {
			
			return true;
		}
		
		touch ( $cache_file );
		$file = $path_to;
		//require_once ($dir . 'options.php');
		

		if (stristr ( $file, 'disabled' ) == false) {
			if (stristr ( $file, 'php' ) == TRUE) {
				
				//print "inc $dir.$file      ";
			//	p($file);
				require_once ($file);
			
			}
		}
	
	} // end db_setup
	

	/**
	 * Function set_db_tables
	 *
	 * @desc refresh tables in DB
	 * @access		public
	 * @category	Init API
	 * @subpackage		Init
	 * @author		Peter Ivanov
	 * @link		http://ooyes.net
	 * @param		varchar $table_name to alter table
	 * @param		array $fields_to_add to add new column
	 * @param		array $column_for_not_drop for not drop
	 */
	function set_db_tables($table_name, $fields_to_add, $column_for_not_drop = array()) {
		//set $column_for_not_drop
		//return false;
		

		$dir = APPPATH . '/models/db/';
		$dir2 = CACHEDIR_ROOT . '/db_tmp/';
		
		if (is_dir ( $dir ) == false) {
			@mkdir ( $dir );
		}
		if (is_dir ( $dir2 ) == false) {
			@mkdir ( $dir2 );
		}
		
		if (is_writable ( $dir ) == false) {
			@chmod ( $dir, 0777 );
		
		}
		
		if (is_writable ( $dir2 ) == false) {
			@chmod ( $dir2, 0777 );
		}
		
		$function_cache_id = false;
		$args = func_get_args ();
		foreach ( $args as $k => $v ) {
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		}
		$function_cache_id = __FUNCTION__ . '_' . $table_name . '_' . md5 ( $function_cache_id );
		if (is_file ( $dir2 . $function_cache_id ) == false) {
			@touch ( $dir2 . $function_cache_id );
			//@file_put_contents($dir2 . $function_cache_id , serialize($fields_to_add) );
			

			if ($table_name != 'firecms_sessions') { //Codeigniter System Table
				if (empty ( $column_for_not_drop ))
					$column_for_not_drop = array ('id' );
				
				$sql = "show columns from $table_name";
				$query = CI::db ()->query ( $sql );
				$columns = $query->result_array ();
				
				$exisiting_fields = array ();
				$no_exisiting_fields = array ();
				
				foreach ( $columns as $fivesdraft ) {
					$fivesdraft = array_change_key_case ( $fivesdraft, CASE_LOWER );
					$exisiting_fields [strtolower ( $fivesdraft ['field'] )] = true;
				}
				
				for($i = 0; $i < count ( $columns ); $i ++) {
					$column_to_move = true;
					for($j = 0; $j < count ( $fields_to_add ); $j ++) {
						if (in_array ( $columns [$i] ['Field'], $fields_to_add [$j] )) {
							$column_to_move = false;
						}
					}
					$sql = false;
					if ($column_to_move) {
						if (! empty ( $column_for_not_drop )) {
							if (! in_array ( $columns [$i] ['Field'], $column_for_not_drop )) {
								$sql = "alter table $table_name drop column {$columns[$i]['Field']} ";
							}
						} else {
							$sql = "alter table $table_name drop column {$columns[$i]['Field']} ";
						}
						if ($sql)
							CI::db ()->query ( $sql );
					}
				}
				
				foreach ( $fields_to_add as $the_field ) {
					$the_field [0] = strtolower ( $the_field [0] );
					
					$sql = false;
					if ($exisiting_fields [$the_field [0]] != true) {
						$sql = "alter table $table_name add column {$the_field[0]} {$the_field[1]} ";
						CI::db ()->query ( $sql );
					} else {
						//$sql = "alter table $table_name modify {$the_field[0]} {$the_field[1]} ";
					//CI::db()->query ( $sql );
					}
				
				}
			}
		} //set_db_tables
	}
	
	/**
	 * Add new table index if not exists
	 * @example $this->addIndex('I_messages_parent_id', $table_name, array('parent_id'));
	 *
	 * @param unknown_type $aIndexName Index name
	 * @param unknown_type $aTable Table name
	 * @param unknown_type $aOnColumns Involved columns
	 */
	public function addIndex($aIndexName, $aTable, $aOnColumns, $indexType = false) {
		$columns = implode ( ',', $aOnColumns );
		
		$query = CI::db ()->query ( "SHOW INDEX FROM {$aTable} WHERE Key_name = '{$aIndexName}';" );
		
		if ($indexType != false) {
			
			$index = $indexType;
		} else {
			$index = " INDEX ";
			
		//FULLTEXT
		}
		
		if ($query->num_rows () == 0) {
			$q = "
				ALTER TABLE {$aTable} ADD $index `{$aIndexName}` ({$columns});
			";
			//var_dump($q);
			CI::db ()->query ( $q );
		}
	
	}
	
	/**
	 * Set table's engine
	 *
	 * @param unknown_type $aTable
	 * @param unknown_type $aEngine
	 */
	public function setEngine($aTable, $aEngine = 'InnoDB') {
		CI::db ()->query ( "ALTER TABLE {$aTable} ENGINE={$aEngine};" );
	}
	
	/**
	 * Create foreign key if not exists
	 *
	 * @param unknown_type $aFKName Foreign key name
	 * @param unknown_type $aTable Source table name
	 * @param unknown_type $aColumns Source columns
	 * @param unknown_type $aForeignTable Foreign table name
	 * @param unknown_type $aForeignColumns Foreign columns
	 * @param unknown_type $aOptions On update and on delete options
	 */
	public function addForeignKey($aFKName, $aTable, $aColumns, $aForeignTable, $aForeignColumns, $aOptions = array()) {
		$query = CI::db ()->query ( "
			SELECT
				*
			FROM
				INFORMATION_SCHEMA.TABLE_CONSTRAINTS
			WHERE
				CONSTRAINT_TYPE = 'FOREIGN KEY'
 			AND
 				constraint_name = '{$aFKName}'
		;" );
		
		if ($query->num_rows () == 0) {
			
			$columns = implode ( ',', $aColumns );
			$fColumns = implode ( ',', $aForeignColumns );
			;
			$onDelete = 'ON DELETE ' . (isset ( $aOptions ['delete'] ) ? $aOptions ['delete'] : 'NO ACTION');
			$onUpdate = 'ON UPDATE ' . (isset ( $aOptions ['update'] ) ? $aOptions ['update'] : 'NO ACTION');
			
			$q = "
				ALTER TABLE {$aTable}
			    ADD CONSTRAINT `{$aFKName}`
			    FOREIGN KEY
			    ({$columns})
			    REFERENCES {$aForeignTable} ($fColumns)
			    {$onDelete}
			    {$onUpdate}
			";
			
			CI::db ()->query ( $q );
		}
	
	}

}

function parseTextForEmail($text) {
	$email = array ();
	$invalid_email = array ();
	
	$text = ereg_replace ( "[^A-Za-z._0-9@ ]", " ", $text );
	
	$token = trim ( strtok ( $text, " " ) );
	
	while ( $token !== "" ) {
		
		if (strpos ( $token, "@" ) !== false) {
			
			$token = ereg_replace ( "[^A-Za-z._0-9@]", "", $token );
			
			//checking to see if this is a valid email address
			if (is_valid_email ( $email ) !== true) {
				$email [] = strtolower ( $token );
			} else {
				$invalid_email [] = strtolower ( $token );
			}
		}
		
		$token = trim ( strtok ( " " ) );
	}
	
	$email = array_unique ( $email );
	$invalid_email = array_unique ( $invalid_email );
	
	return array ("valid_email" => $email, "invalid_email" => $invalid_email );

}

function is_valid_email($email) {
	if (eregi ( "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.([a-z]){2,4})$", $email ))
		return true;
	else
		return false;
}

?>
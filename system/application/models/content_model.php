<?php
if (! defined ( 'BASEPATH' ))
	
	exit ( 'No direct script access allowed' );

/** * Microweber * * An open source CMS and application development framework for PHP 5.1 or newer * * @package     Microweber * @author      Peter Ivanov * @copyright   Copyright (c), Mass Media Group, LTD. * @license     http://ooyes.net * @link        http://ooyes.net * @since       Version 1.0 */

// ------------------------------------------------------------------------

/**  * Content class * * @desc Functions for manipulation the main content of the cms, mainly in the content table from the DB * @access      public * @category    Content API * @subpackage      Core * @author      Peter Ivanov * @link        http://ooyes.net */

class content_model extends Model {
	
	function __construct() {
		
		parent::Model ();
		
		// ping google on random? must be set on cronjob		$rand = rand ( 0, 100 );
		
		if ($rand < 5) {
			
			$this->content_pingServersWithNewContent ();
		
		}
	
	}
	
	/**	 * @desc Function to save content into the content_table	 * @param array	 * @param boolean	 * @return string | the id saved	 * @author      Peter Ivanov	 * @version 1.0	 * @since Version 1.0	 */
	
	function saveContent($data, $delete_the_cache = true) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_content'];
		
		if (empty ( $data )) {
			
			return false;
		
		}
		
		$data_to_save = $data;
		
		//var_dump($data);		

		$more_categories_to_delete = array ();
		if (intval ( $data ['id'] ) != 0) {
			
			$q = "SELECT * from $table where id='{$data_to_save['id']}' ";
			
			$q = $this->core_model->dbQuery ( $q );
			
			$thecontent_title = $q [0] ['content_title'];
			
			$q = $q [0] ['content_url'];
			
			$thecontent_url = $q;
			
			$more_categories_to_delete = $this->taxonomyGetTaxonomyIdsForContentId ( $data ['id'] );
			
		//var_dump($thecontent_url);		

		} else {
			
			//print 'asd3';			

			$thecontent_url = $data ['content_url'];
			
			$thecontent_title = $data ['content_title'];
		
		}
		
		if ($thecontent_url != false) {
			
			if ($data ['content_url'] == $thecontent_url) {
				
				//print 'asd2';				

				$data ['content_url'] = $thecontent_url;
			
			} else {
			
			}
			
			if (strval ( $data ['content_url'] ) == '') {
				
				//print 'asd1';				

				$data ['content_url'] = $thecontent_url;
			
			}
			
			if ((strval ( $data ['content_url'] ) == '') and (strval ( $thecontent_url ) == '')) {
				
				//print 'asd';				

				$data ['content_url'] = $this->core_model->url_title ( $thecontent_title );
			
			}
		
		} else {
			
			$data ['content_url'] = $this->core_model->url_title ( $thecontent_title );
		
		}
		
		$data ['content_url'] = $this->core_model->url_title ( $data ['content_url'] );
		
		if (strval ( $data ['content_url'] ) == '') {
			
			$data ['content_url'] = $this->core_model->url_title ( $data ['content_title'] );
		
		}
		
		$date123 = date ( "YmdHis" );
		
		$q = "select id, content_url from $table where content_url LIKE '{$data ['content_url']}'";
		
		$q = $this->core_model->dbQuery ( $q );
		
		if (! empty ( $q )) {
			
			$q = $q [0];
			
			if ($data ['id'] != $q ['id']) {
				
				$data ['content_url'] = $data ['content_url'] . '-' . $date123;
			
			}
		
		}
		
		//  var_dump($data_to_save);		

		if (strval ( $data_to_save ['content_url'] ) == '') {
			
			$data_to_save ['content_url'] = $data_to_save ['content_url'] . '-' . $date123;
		
		}
		
		if (strval ( $data_to_save ['content_title'] ) == '') {
			
			$data_to_save ['content_title'] = 'post-' . $date123;
		
		}
		
		$data_to_save ['content_url'] = strtolower ( reduce_double_slashes ( $data ['content_url'] ) );
		
		$data_to_save ['content_url_md5'] = md5 ( $data_to_save ['content_url'] );
		
		$data_to_save_options = array ();
		
		$table = $table;
		
		if ($delete_the_cache == true) {
			//	$this->core_model->cleanCacheGroup ( 'content' );
		//	$this->core_model->cleanCacheGroup ( 'media' );
		//	$this->core_model->cleanCacheGroup ( 'global' );
		}
		
		if ($data_to_save ['is_home'] == 'y') {
			$sql = "UPDATE $table set is_home='n'   ";
			$q = $this->db->query ( $sql );
		}
		
		//parse images		

		$data_to_save ['content_body'] = $this->parseContentBodyItems ( $data_to_save ['content_body'], $data_to_save ['content_title'] );
		
		if ($data_to_save ['content_filename_sync_with_editor'] == 'y') {
		
		}
		
		foreach ( $data_to_save as $k => $v ) {
			
			if (is_array ( $v ) == false) {
				
				$data_to_save [$k] = $this->parseContentBodyItems ( $v, $data_to_save ['content_title'] );
			
			}
		
		}
		
		if ($data_to_save ['content_body_filename'] != false) {
			
			if (trim ( $data_to_save ['content_body_filename'] ) != '') {
				
				$the_active_site_template = $this->content_model->optionsGetByKey ( 'curent_template' );
				
				$the_active_site_template_dir = TEMPLATEFILES . $the_active_site_template . '/content_files/';
				
				$the_active_site_template_dir_backup = TEMPLATEFILES . $the_active_site_template . '/content_files/backup/';
				
				$the_active_site_template_dir_backup_ymd = TEMPLATEFILES . $the_active_site_template . '/content_files/backup/' . date ( "Y-m-d" ) . '/';
				
				if (is_dir ( $the_active_site_template_dir ) == false) {
					
					@mkdir ( $the_active_site_template_dir );
				
				}
				
				if (is_dir ( $the_active_site_template_dir_backup ) == false) {
					
					@mkdir ( $the_active_site_template_dir_backup );
				
				}
				
				if (is_dir ( $the_active_site_template_dir_backup_ymd ) == false) {
					
					@mkdir ( $the_active_site_template_dir_backup_ymd );
					
					if (is_file ( $the_active_site_template_dir_backup_ymd . 'index.php' ) == false) {
						
						@touch ( $the_active_site_template_dir_backup_ymd . 'index.php' );
						
						@file_put_contents ( $the_active_site_template_dir_backup_ymd . 'index.php', 'Directory access denied by Microweber' );
					
					}
				
				}
				
				if (is_dir ( $the_active_site_template_dir ) == false) {
					
					@mkdir_recursive ( $the_active_site_template_dir );
				
				}
				
				if (is_file ( $the_active_site_template_dir . $data_to_save ['content_body_filename'] ) == false) {
					
					@touch ( $the_active_site_template_dir . $data_to_save ['content_body_filename'] );
				
				} else {
					
					@copy ( $the_active_site_template_dir . $data_to_save ['content_body_filename'], $the_active_site_template_dir_backup_ymd . date ( "H:i:s" ) . '_' . $data_to_save ['content_body_filename'] );
				
				}
				
				$temp1 = ($data_to_save ['content_body']);
				
				if (trim ( $data_to_save ['content_body'] ) != '') {
					
					@file_put_contents ( $the_active_site_template_dir . $data_to_save ['content_body_filename'], $temp1 );
				
				}
			
			}
		
		}
		
		//var_dump ( $data_to_save );
		

		//return false;		

		//exit ();
		

		$save = $this->core_model->saveData ( $table, $data_to_save );
		$id = $save;
		
		if ($data_to_save ['content_type'] == 'page') {
			
			if (! empty ( $data_to_save ['menus'] )) {
				
				//housekeep				

				$this->removeContentFromUnusedMenus ( $save, $data_to_save ['menus'] );
				
				foreach ( $data_to_save ['menus'] as $menu_item ) {
					
					$to_save = array ();
					
					$to_save ['item_type'] = 'menu_item';
					
					$to_save ['item_parent'] = $menu_item;
					
					$to_save ['content_id'] = intval ( $save );
					
					$to_save ['item_title'] = $data_to_save ['content_title'];
					
					$this->saveMenu ( $to_save );
					
					$this->core_model->cleanCacheGroup ( 'menus' );
				
				}
			
			}
		
		}
		
		//$this->core_model->cacheDeleteAll (); 		

		if ($data_to_save ['preserve_cache'] == false) {
			if (intval ( $data_to_save ['content_parent'] ) != 0) {
				$this->core_model->cleanCacheGroup ( 'content' . DIRECTORY_SEPARATOR . intval ( $data_to_save ['content_parent'] ) );
			}
			$this->core_model->cleanCacheGroup ( 'content' . DIRECTORY_SEPARATOR . $id );
			$this->core_model->cleanCacheGroup ( 'content' . DIRECTORY_SEPARATOR . '0' );
			$this->core_model->cleanCacheGroup ( 'content' . DIRECTORY_SEPARATOR . 'global' );
			
			if (! empty ( $data_to_save ['taxonomy_categories'] )) {
				foreach ( $data_to_save ['taxonomy_categories'] as $cat ) {
					//var_dump('taxonomy' . DIRECTORY_SEPARATOR . intval ( $cat ) );
					$this->core_model->cleanCacheGroup ( 'taxonomy' . DIRECTORY_SEPARATOR . intval ( $cat ) );
				}
				$this->core_model->cleanCacheGroup ( 'taxonomy' . DIRECTORY_SEPARATOR . '0' );
				$this->core_model->cleanCacheGroup ( 'taxonomy' . DIRECTORY_SEPARATOR . 'global' );
			
			}
			
			if (! empty ( $more_categories_to_delete )) {
				foreach ( $more_categories_to_delete as $cat ) {
					$this->core_model->cleanCacheGroup ( 'taxonomy' . DIRECTORY_SEPARATOR . intval ( $cat ) );
				}
			}
		}
		return $save;
	
	}
	
	function parseContentBodyItems($original_content, $title) {
		
		$original_content = $original_content;
		
		$rem = false;
		
		$original_content = str_replace ( $rem, ' ', $original_content );
		
		$rem = ' ';
		
		$original_content = str_ireplace ( $rem, ' ', $original_content );
		
		$original_content = str_ireplace ( 'class=mcevisualaid>', ' ', $original_content );
		
		//  $original_content = html_entity_decode($original_content);		

		$site_url = site_url ();
		
		$original_content = str_ireplace ( $site_url, '{SITEURL}', $original_content );
		
		$content_item = $original_content;
		
		$possible_filename = $this->core_model->url_title ( $title, 'dash', true );
		
		$possible_filename = string_cyr2lat ( $possible_filename );
		
		$possible_filename = $possible_filename . '-' . date ( "Ymdhis" ) . rand ( 1, 99 );
		
		$possible_filename = str_ireplace ( '&', '_', $possible_filename );
		
		$possible_filename = str_ireplace ( ';', '_', $possible_filename );
		
		$possible_filename = str_ireplace ( '`', '_', $possible_filename );
		
		$possible_filename = str_ireplace ( '"', '_', $possible_filename );
		
		$possible_filename = str_ireplace ( "'", '_', $possible_filename );
		
		$possible_filename = str_ireplace ( "%", '_', $possible_filename );
		
		$possible_filename = str_ireplace ( "*", '_', $possible_filename );
		
		$possible_filename = str_ireplace ( "#", '_', $possible_filename );
		
		$possible_filename = str_ireplace ( "@", '_', $possible_filename );
		
		$possible_filename = str_ireplace ( "!", '_', $possible_filename );
		
		$possible_filename = str_ireplace ( '$', '_', $possible_filename );
		
		$possible_filename = str_ireplace ( '/', '_', $possible_filename );
		
		$possible_filename = str_ireplace ( '\\', '_', $possible_filename );
		
		$possible_filename = str_ireplace ( '[', '_', $possible_filename );
		
		$possible_filename = str_ireplace ( ']', '_', $possible_filename );
		
		$possible_filename = str_ireplace ( 'Ã¢â‚¬â"¢', '_', $possible_filename );
		
		if (strval ( $original_content ) == '') {
			
			return false;
		
		}
		
		$input = $content_item;
		
		//var_dump($input);		

		//  exit;		

		//		

		$regexp = "<img\s[^>]*src=(\"??)([^\" >]*?)\\1[^>]*";
		
		$images = array ();
		
		if (preg_match_all ( "/$regexp/siU", $input, $matches, PREG_SET_ORDER )) {
			
			foreach ( $matches as $match ) {
				
				# $match[2] = link address				

				# $match[3] = link text				

				$images [] = $match [2];
			
			}
		
		}
		
		$dir = MEDIAFILES . 'downloaded/';
		
		if (is_dir ( $dir ) == false) {
			
			@mkdir ( $dir );
		
		}
		
		@touch ( $dir . 'index.html' );
		
		//mkdir		

		$media_url = $this->core_model->mediaGetUrlDir ();
		
		if (! empty ( $images )) {
			
			foreach ( $images as $image ) {
				
				if ((stristr ( $image, '.jpg' ) == true) or (stristr ( $image, '.png' ) == true) or (stristr ( $image, '.gif' ) == true) or (stristr ( $image, '.bmp' ) == true) or (stristr ( $image, '.jpeg' ) == true)) 

				{
					
					$orig_image = $image;
					
					if (stristr ( $image, '{MEDIAURL}' == false )) {
						
						if (stristr ( $image, $media_url ) == true) {
						
						} else {
							
							if ($this->core_model->url_IsFile ( $image ) == true) {
								
								$to_get = $image;
							
							} else {
								
								$image = 'http://maksoft.net/' . $image;
								
								if ($this->core_model->url_IsFile ( $image ) == true) {
									
									$to_get = $image;
								
								}
							
							}
							
							if ($this->core_model->url_IsFile ( $image ) == true) {
								
								if (stristr ( $image, $media_url ) == false) {
									
									//print 'file: ' . $image;									

									$parts = explode ( '/', $image );
									
									$currentFile = $parts [count ( $parts ) - 1];
									
									$orig_file = $currentFile;
									
									$ext = substr ( $image, strrpos ( $image, '.' ) + 1 );
									
									//$to_save =									

									//exit($ext);									

									$orig_file_clean = strip_punctuation ( $orig_file );
									
									$orig_file_clean = str_replace ( '.', '_', $orig_file_clean );
									
									$orig_file_clean = str_replace ( '.', '_', $orig_file_clean );
									
									$orig_file_clean = str_replace ( '.', '_', $orig_file_clean );
									
									$orig_file_clean = str_replace ( '=', '_', $orig_file_clean );
									
									$orig_file_clean = str_replace ( '?', '_', $orig_file_clean );
									
									$orig_file_clean = str_ireplace ( '&', '_', $orig_file_clean );
									
									$orig_file_clean = str_ireplace ( ';', '_', $orig_file_clean );
									
									$orig_file_clean = str_ireplace ( '`', '_', $orig_file_clean );
									
									$orig_file_clean = str_ireplace ( '"', '_', $orig_file_clean );
									
									$orig_file_clean = str_ireplace ( "'", '_', $orig_file_clean );
									
									$orig_file_clean = str_ireplace ( "%", '_', $orig_file_clean );
									
									$orig_file_clean = str_ireplace ( "*", '_', $orig_file_clean );
									
									$orig_file_clean = str_ireplace ( "#", '_', $orig_file_clean );
									
									$orig_file_clean = str_ireplace ( "@", '_', $orig_file_clean );
									
									$orig_file_clean = str_ireplace ( "!", '_', $orig_file_clean );
									
									$orig_file_clean = str_ireplace ( '$', '_', $orig_file_clean );
									
									$orig_file_clean = str_ireplace ( '/', '_', $orig_file_clean );
									
									$orig_file_clean = str_ireplace ( '\\', '_', $orig_file_clean );
									
									$orig_file_clean = str_ireplace ( '[', '_', $orig_file_clean );
									
									$orig_file_clean = str_ireplace ( ']', '_', $orig_file_clean );
									
									$orig_file_clean = str_ireplace ( 'Ã¢â‚¬â"¢', '_', $orig_file_clean );
									
									$orig_file_clean = $orig_file_clean . '.' . $ext;
									
									if (is_file ( $dir . $possible_filename ) == false) {
										
										$currentFile = $possible_filename . '-' . $orig_file_clean;
									
									} else {
										
										if (is_file ( $dir . $currentFile ) == true) {
											
											$currentFile = $possible_filename . '_' . $orig_file_clean;
										
										}
										
										if (is_file ( $dir . $currentFile ) == true) {
											
											$currentFile = $possible_filename . '_' . date ( "Ymdhis" ) . '_' . $orig_file_clean;
										
										}
										
										if (is_file ( $dir . $currentFile ) == true) {
											
											$currentFile = date ( "Ymdhis" ) . '_' . $orig_file_clean;
										
										}
									
									}
									
									//get									

									/*  var_dump ( $image );                                print "<hr>";                                var_dump ( $dir . $currentFile );                                print "<hr>";                                print "<hr>";*/
									
									//$this->core_model->url_getPageToFile ( $image, $dir . $currentFile );									

									//p($orig_image,1);									

									//p($currentFile,1);									

									CurlTool::downloadFile ( $image, $dir . $currentFile, false );
									
									$the_new_image = '{MEDIAURL}' . 'downloaded/' . $currentFile;
									
									//  $content_item = str_ireplace ( $image, $the_new_image, $content_item );									

									$content_item = str_ireplace ( $orig_image, $the_new_image, $content_item );
									
									$content_item = str_ireplace ( $media_url, '{MEDIAURL}', $content_item );
								
								}
								
							//var_dump ( $currentFile, $image, $the_new_image );							

							} else {
								
							//print 'no file: ' . $image;							

							}
							
						//var_dump ( $content_item );						

						}
					
					}
				
				}
			
			}
		
		}
		
		//  var_dump ( $content_item );		

		//exit ();		

		return $content_item;
	
	}
	
	function deleteContent($id) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_content'];
		
		$table_menus = $cms_db_tables ['table_menus'];
		
		$table_taxonomy = $cms_db_tables ['table_taxonomy'];
		
		if (intval ( $id ) == 0) {
			
			return false;
		
		}
		
		$data = array ();
		
		$data ['id'] = $id;
		
		$del = $this->core_model->deleteData ( $table, $data, 'content' );
		
		$data = array ();
		
		$data ['to_table'] = 'table_content';
		
		$data ['to_table_id'] = $id;
		
		$del = $this->core_model->deleteData ( $table_taxonomy, $data );
		
		$table_comments = $cms_db_tables ['table_comments'];
		
		$data = array ();
		
		$data ['to_table'] = 'table_content';
		
		$data ['to_table_id'] = $id;
		
		$del = $this->core_model->deleteData ( $table_comments, $data );
		
		$table_comments = $cms_db_tables ['table_taxonomy'];
		
		$data = array ();
		
		$data ['to_table'] = 'table_content';
		
		$data ['to_table_id'] = $id;
		
		$del = $this->core_model->deleteData ( $table_comments, $data );
		
		$table_v = $cms_db_tables ['table_votes'];
		
		$data = array ();
		
		$data ['to_table'] = 'table_content';
		
		$data ['to_table_id'] = $id;
		
		$del = $this->core_model->deleteData ( $table_v, $data );
		
		$table = $cms_db_tables ['table_reports'];
		
		$data = array ();
		
		$data ['to_table'] = 'table_content';
		
		$data ['to_table_id'] = $id;
		
		$del = $this->core_model->deleteData ( $table, $data );
		
		$table = $cms_db_tables ['table_custom_fields'];
		
		$data = array ();
		
		$data ['to_table'] = 'table_content';
		
		$data ['to_table_id'] = $id;
		
		$del = $this->core_model->deleteData ( $table, $data );
		
		$table = $cms_db_tables ['table_users_notifications'];
		
		$data = array ();
		
		$data ['to_table'] = 'table_content';
		
		$data ['to_table_id'] = $id;
		
		$del = $this->core_model->deleteData ( $table, $data );
		
		$this->core_model->cleanCacheGroup ( 'content' . DIRECTORY_SEPARATOR . $id );
		$this->core_model->cleanCacheGroup ( 'content' . DIRECTORY_SEPARATOR . '0' );
		$this->core_model->cleanCacheGroup ( 'content' . DIRECTORY_SEPARATOR . 'global' );
		
	//$table = $table_menus;	

	//$data = array ( );	

	//$data ['content_id'] = $id;	

	//$del = $this->core_model->deleteData ( $table, $data, 'menus' );	

	//$this->fixMenusPositions ();	

	//$data = array ( );	

	//$data ['to_table'] = 'table_content';	

	//$data ['to_table_id'] = $id;	

	//$del = $this->core_model->deleteData ( $table_taxonomy, $data, 'taxonomy' );	

	//$this->core_model->cleanCacheGroup ( 'content' );
	

	//  $this->core_model->cleanCacheGroup ( 'core' );	

	//$this->core_model->cleanCacheGroup ( 'global' );	

	}
	
	function getPostByURLAndCache($content_id, $url, $no_recursive = false) {
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$content_id = intval ( $content_id );
		$cache_group = 'content/' . $content_id;
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		$content = $this->getPostByURL ( $content_id, $url, $no_recursive = false );
		
		$this->core_model->cacheWriteAndEncode ( $content, $function_cache_id, $cache_group );
		
		return $content;
	
	}
	
	function getPostByURL($content_id, $url, $no_recursive = false) {
		
		//  header("Content-type: text/plain; charset=UTF-8");		

		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$content_id = intval ( $content_id );
		$cache_group = 'content/' . $content_id;
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		global $cms_db_tables;
		
		if (strval ( $url ) == '') {
			
			return false;
		
		}
		
		$id = array ();
		
		$id ['id'] = $content_id;
		
		$id = $this->contentGetById ( $id );
		
		$the_real_url = str_replace_count ( $id ['content_url'], '', $url, 1 );
		
		$the_real_url = trim_slashes ( $the_real_url );
		
		$url = $the_real_url;
		
		$table = $cms_db_tables ['table_content'];
		
		$url = strtolower ( $url );
		
		$content_url_md5 = md5 ( $url );
		
		$sql = "SELECT id, content_url from $table where content_url='$url' and content_type='post'   order by updated_on desc limit 0,1 ";
		
		$q = $this->core_model->dbQuery ( $sql, md5 ( $sql ), 'content/global' );
		
		$result = $q;
		
		//var_dump($result);		

		if (! empty ( $result )) {
			
			$content = $result [0];
			
			$content = $content ['id'];
		
		} else {
			
			return false;
		
		}
		
		$data = array ();
		
		$data ['id'] = $content;
		
		$content = $this->contentGetByIdAndCache ( $data ['id'] );
		
		if (empty ( $content )) {
			
			return false;
		
		} else {
			
			$this->core_model->cacheWriteAndEncode ( $content, $function_cache_id, $cache_group );
			
			return $content;
		
		}
	
	}
	
	function getContentByURLAndCache($url, $no_recursive = false) {
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_group = 'content/global';
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		} else {
			
			$to_cache = $this->getContentByURL ( $url, $no_recursive = $no_recursive );
			
			//var_dump($to_cache);			

			$this->core_model->cacheWriteAndEncode ( $to_cache, $function_cache_id, $cache_group );
			
			return $to_cache;
		
		}
	
	}
	
	function getPageByURLAndCache($url, $no_recursive = false) {
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_group = 'content/global';
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		} else {
			if (($cache_content) == '--false--') {
				return false;
			}
			$to_cache = $this->getPageByURL ( $url, $no_recursive = $no_recursive );
			if ($to_cache == false) {
				$to_cache == '--false--';
			}
			//var_dump($to_cache);			

			$this->core_model->cacheWriteAndEncode ( $to_cache, $function_cache_id, $cache_group );
			
			return $to_cache;
		
		}
	
	}
	
	function getPageByURL($url = false, $no_recursive = false, $try_to_return_post_by_url = false) {
		
		global $cms_db_tables;
		
		//return false;		

		if (strval ( $url ) == '') {
			
			//return false;			

			$url = getCurentURL ();
		
		}
		
		//var_dump($url);		

		$table = $cms_db_tables ['table_content'];
		
		$url = strtolower ( $url );
		
		$url = addslashes ( $url );
		
		$sql = "SELECT id,content_url_md5,content_url from $table where content_url='$url' and is_active='y'   and content_type='page' order by updated_on desc limit 0,1 ";
		
		//print $sql;		

		//exit ();		

		$q = $this->core_model->dbQuery ( $sql, md5 ( $sql ), 'content/global' );
		
		$result = $q;
		
		$content = $result [0];
		
		if ($try_to_return_post_by_url == true) {
			
			if (empty ( $content )) {
				
				$sql = "SELECT id,content_url from $table where content_url='$url' and is_active='y'   and content_type='post' order by updated_on desc limit 0,1 ";
				
				$q = $this->core_model->dbQuery ( $sql, md5 ( $sql ), 'content/global' );
				
				$result = $q;
				
				$content = $result [0];
			
			}
			
			if (! empty ( $content )) {
				
				//var_dump($content['content_body']);				

				$content = $this->contentGetByIdAndCache ( $content ['id'] );
			
			}
		
		}
		
		//var_dump($content);		

		//exit;		

		if (! empty ( $content )) {
			
			$get_by_id = array ();
			
			$get_by_id ['id'] = $content ['id'];
			
			$get_by_id = $this->contentGetByIdAndCache ( $get_by_id ['id'] );
			
			return $get_by_id;
		
		}
		
		if ($no_recursive == false) {
			
			if (empty ( $content ) == true) {
				
				///var_dump ( $url );				

				$segs = explode ( '/', $url );
				
				$segs_qty = count ( $segs );
				
				for($counter = 0; $counter <= $segs_qty; $counter += 1) {
					
					$test = array_slice ( $segs, 0, $segs_qty - $counter );
					
					$test = array_reverse ( $test );
					
					//var_dump($test);					

					//$url = implode ( '/', $test );					

					$url = $this->getPageByURLAndCache ( $test [0], true );
					
					//var_dump ( $url );					

					if (! empty ( $url )) {
						
						return $url;
					
					}
				
				}
			
			}
		
		} else {
			
			$get_by_id = array ();
			
			$get_by_id ['id'] = $content ['id'];
			
			$get_by_id = $this->contentGetByIdAndCache ( $content ['id'] );
			
			return $get_by_id;
		
		}
	
	}
	
	function getContentByURL($url, $no_recursive = false) {
		
		global $cms_db_tables;
		
		if (strval ( $url ) == '') {
			
			//return false;			

			$url = getCurentURL ();
		
		}
		
		//var_dump($url);		

		$table = $cms_db_tables ['table_content'];
		
		$url = strtolower ( $url );
		
		$url = addslashes ( $url );
		$sql = "SELECT id,content_url from $table where content_url='$url'  order by updated_on desc limit 0,1 ";
		
		//print $sql;		

		//exit ();		

		$q = $this->core_model->dbQuery ( $sql, md5 ( $sql ), 'content/global' );
		
		$result = $q;
		
		$content = $result [0];
		
		if (! empty ( $content )) {
			
			$get_by_id = $this->contentGetByIdAndCache ( $content ['id'] );
			
			return $get_by_id;
		
		}
		
		if ($no_recursive == false) {
			
			if (empty ( $content ) == true) {
				
				///var_dump ( $url );				

				$segs = explode ( '/', $url );
				
				$segs_qty = count ( $segs );
				
				for($counter = 0; $counter <= $segs_qty; $counter += 1) {
					
					$test = array_slice ( $segs, 0, $segs_qty - $counter );
					
					$test = array_reverse ( $test );
					
					$url = $this->getContentByURL ( $test [0], true );
					
					if (! empty ( $url )) {
						
						return $url;
					
					}
				
				}
			
			}
		
		} else {
			$content ['id'] = intval ( $content ['id'] );
			$get_by_id = $this->contentGetById ( $content ['id'] );
			
			return $get_by_id;
		
		}
	
	}
	
	function getContentURLByIdAndCache($id) {
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		$id = intval ( $id );
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, $cache_group = 'content/' . $id );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		$the_url = $this->getContentURLById ( $id );
		
		$cache = $this->core_model->cacheWriteAndEncode ( $the_url, $function_cache_id, $cache_group = 'content/' . $id );
		
		return $the_url;
	
	}
	
	function getContentURLById($id) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_content'];
		
		$id = intval ( $id );
		$content = $this->contentGetByIdAndCache ( $id );
		
		if ($content ['content_type'] == 'page') {
			
			$url = site_url ( $content ['content_url'] );
			
			return $url;
		
		}
		
		if ($content ['content_type'] == 'post') {
			
			$url = $this->contentGetHrefForPostId ( $id );
			
			//  $url = site_url ( $content ['content_url'] );			

			return $url;
		
		}
		
		return false;
		
	//var_dump ( $content );	

	}
	
	function getContentHomepage() {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_content'];
		
		//var_dump($cms_db_tables);		

		$sql = "SELECT * from $table where is_home='y' and is_active='y' order by updated_on desc limit 0,1 ";
		
		$q = $this->core_model->dbQuery ( $sql, 'getContentHomepage' . md5 ( $sql ), 'content/global' );
		//var_dump($q);
		$result = $q;
		
		$content = $result [0];
		
		//var_dump($content);		

		return $content;
	
	}
	
	//double function but why? next time watch more :)	

	function contentDelete($id) {
		
		$this->deleteContent ( $id );
	
	}
	
	function contentGetPages($is_active = false) {
		
		$data = array ();
		
		$data ['content_type'] = 'page';
		
		if ($is_active != false) {
			
			$data ['is_active'] = $is_active;
		
		}
		
		$pages = $this->getContent ( $data );
		
		return $pages;
	
	}
	
	function getParentPagesIdsForPageIdAndCache($id) {
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		$id = intval ( $id );
		$cache_group = 'content/' . $id;
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, $cache_content );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		$data = $this->getParentPagesIdsForPageId ( $id );
		
		$cache = $this->core_model->cacheWriteAndEncode ( $data, $function_cache_id, $cache_group );
		
		return $data;
	
	}
	
	function getParentPagesIdsForPageId($id) {
		
		if (intval ( $id ) == 0) {
			
			return FALSE;
		
		}
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_content'];
		
		$ids = array ();
		
		$data = array ();
		
		$id = intval ( $id );
		
		$q = " select id, content_parent,  content_type  from $table where id = $id";
		
		//$taxonomies = $this->core_model->dbQuery ( $q, $cache_id = md5 ( $q ), $cache_group = 'content' );		

		$taxonomies = $this->core_model->dbQuery ( $q );
		
		//  var_dump($q);		

		//  var_dump($taxonomies);		

		//  exit;		

		if (! empty ( $taxonomies )) {
			
			foreach ( $taxonomies as $item ) {
				
				if (intval ( $item ['id'] ) != 0) {
					
					$ids [] = $item ['content_parent'];
					
					//} else {					

					$next = false;
					
					if (intval ( $item ['content_parent'] ) != 0) {
						
						$next = $this->getParentPagesIdsForPageId ( $item ['content_parent'] );
					
					}
					
					if (! empty ( $next )) {
						
						foreach ( $next as $n ) {
							
							if ($n != '') {
								
								if (intval ( $n ) != 0) {
									
									$ids [] = $n;
								
								}
							
							}
						
						}
					
					}
				
				}
			
			}
		
		}
		
		if (! empty ( $ids )) {
			
			//var_dump($ids);			

			$ids = array_unique ( $ids );
			
			return $ids;
		
		} else {
			
			return false;
		
		}
	
	}
	
	function contentGetHrefForPostId($id) {
		
		global $cms_db_tables;
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		$content = $this->contentGetByIdAndCache ( $id );
		
		$cats = $this->contentGetActiveCategoriesForPostIdAndCache ( $id );
		
		if (! empty ( $cats )) {
			
			$url = false;
			
			$master = $this->taxonomyGetMasterCategories ();
			
			//.p($master);			

			foreach ( $cats as $c ) {
				
				if ($url == false) {
					
					foreach ( $master as $m ) {
						
						if ($m ['id'] == $c) {
							
							$url = $this->taxonomyGetUrlForTaxonomyIdAndCache ( $m ['id'] ); // . '/' . $content ['content_url'];							

							$url1 = $url . '/' . $content ['content_url'];
							
						//var_dump($url1);						

						}
					
					}
				
				}
			
			}
			
			$the_url = $url1;
		
		} else {
			
			if (intval ( $content ['content_parent'] ) == 0) {
				
				//$the_url = site_url ( 'admin/content/posts_edit/id:' ) . $content ['id'];				

				$the_url = $the_url . '/' . $content ['content_url'];
				
				$the_url = reduce_double_slashes ( site_url ( $the_url ) );
			
			} else {
				
				$the_url = $this->getContentURLById ( $content ['content_parent'] );
				
				$the_url = $the_url . '/' . $content ['content_url'];
				
				$the_url = reduce_double_slashes ( $the_url );
				
			//var_dump($the_url);			

			//var_dump( $content);			

			}
		
		}
		
		//var_dump($cats);		

		/*$cache = base64_encode ( $the_url );        $table_cache = $cms_db_tables ['table_cache'];        $q = "delete from $table_cache where cache_id='$cache_id'  ";        $this->core_model->dbQ ( $q );        $this->core_model->cacheDeleteFile ( $cache_id );        $this->core_model->cacheWriteContent ( $cache_id, $cache );        $q = "INSERT INTO $table_cache set cache_id='$cache_id', cache_group='content' ";        $this->core_model->dbQ ( $q );        */
		
		//cache		

		$cache = $this->core_model->cacheWriteAndEncode ( $the_url, $function_cache_id );
		
		return $the_url;
	
	}
	
	function contentsCheckIfContentExistsById($id) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_content'];
		
		$q = " SELECT count(*) as qty from $table where id=$id";
		
		$q = $this->core_model->dbQuery ( $q );
		
		$q = $q [0] ['qty'];
		
		//var_dump($q);		

		if (intval ( $q ) > 0) {
			
			return true;
		
		} else {
			
			return false;
		
		}
	
	}
	
	function contentsGetTheFirstBlogSectionForCategory($category_id) {
		
		if (intval ( $category_id ) == 0) {
			
			return false;
		
		}
		
		$data = array ();
		
		$data ['content_type'] = 'page';
		
		$data ['content_subtype'] = 'blog_section';
		
		$data ['content_subtype_value'] = $category_id;
		
		$data ['is_active'] = 'y';
		
		$limit [0] = '0';
		
		$limit [1] = '1';
		
		$data = $this->getContentAndCache ( $data, false, $limit );
		
		$data = $data [0];
		
		if (empty ( $data )) {
			
			$taxonomy = $this->taxonomyGetParentItemsAndCache ( $category_id );
			
			//var_dump ( $taxonomy );
			foreach ( $taxonomy as $item ) {
				
				$data1 = array ();
				
				$data1 ['content_type'] = 'page';
				
				$data1 ['content_subtype'] = 'blog_section';
				
				$data1 ['content_subtype_value'] = $item ['id'];
				
				$data1 ['is_active'] = 'y';
				
				$limit [0] = '0';
				
				$limit [1] = '1';
				
				//	function getContent($data, $orderby = false, $limit = false, $count_only = false, $short_data = false, $only_fields = false) {
				

				$data1 = $this->getContentAndCache ( $data1, false, $limit, $count_only = false, $short_data = true );
				
				$data1 = $data1 [0];
				
				if (! empty ( $data1 )) {
					
					return $data1;
				
				}
			
			}
		
		} else {
			
			return $data;
		
		}
	
	}
	
	function contentsGetTheLastBlogSectionForCategory($category_id) {
		
		if (intval ( $category_id ) == 0) {
			
			return false;
		
		}
		
		$data = array ();
		
		$data ['content_type'] = 'page';
		
		$data ['content_subtype'] = 'blog_section';
		
		$data ['content_subtype_value'] = $category_id;
		
		$data ['is_active'] = 'y';
		
		$limit [0] = '0';
		
		$limit [1] = '1';
		
		$data = $this->getContentAndCache ( $data, array ('id', 'desc' ), $limit, $count_only = false, $short_data = true, $only_fields = array ('id', 'content_title' ) );
		
		$data = $data [0];
		//$data = $this->contentGetById($id);
		

		if (empty ( $data )) {
			
			$taxonomy = $this->taxonomyGetChildrenItemsIdsRecursiveAndCache ( $category_id, $type = 'category' );
			
			//var_dump ( $taxonomy );			

			if (! empty ( $taxonomy )) {
				
				foreach ( $taxonomy as $item ) {
					
					$data1 = array ();
					
					$data1 ['content_type'] = 'page';
					
					$data1 ['content_subtype'] = 'blog_section';
					
					$data1 ['content_subtype_value'] = $item ['id'];
					
					$data1 ['is_active'] = 'y';
					
					$limit [0] = '0';
					
					$limit [1] = '1';
					
					$data1 = $this->getContentAndCache ( $data1, false, $limit, $count_only = false, $short_data = true, $only_fields = array ('id', 'content_title' ) );
					
					$data1 = $data1 [0];
					
					if (! empty ( $data1 )) {
						
						return $data1;
					
					}
				
				}
			
			}
		
		}
		
		if (empty ( $data )) {
			
			$taxonomy = $this->taxonomyGetParentItemsAndCache ( $category_id );
			
			//var_dump ( $taxonomy );			

			if (! empty ( $taxonomy )) {
				
				foreach ( $taxonomy as $item ) {
					
					$data1 = array ();
					
					$data1 ['content_type'] = 'page';
					
					$data1 ['content_subtype'] = 'blog_section';
					
					$data1 ['content_subtype_value'] = $item ['id'];
					
					$data1 ['is_active'] = 'y';
					
					$limit [0] = '0';
					
					$limit [1] = '1';
					
					$data1 = $this->getContentAndCache ( $data1, false, $limit, $count_only = false, $short_data = true, $only_fields = array ('id', 'content_title' ) );
					
					$data1 = $data1 [0];
					
					if (! empty ( $data1 )) {
						
						return $data1;
					
					}
				
				}
			
			}
		
		} else {
			
			return $data;
		
		}
	
	}
	
	function contentGetPicturesFromGalleryForContentId($id, $size = 128, $order_direction = "ASC") {
		
		$pics = $this->core_model->mediaGetImages ( 'table_content', $to_table_id = $id, $size = $size, $order_direction = $order_direction );
		
		return $pics;
	
	}
	
	function contentGetThumbnailForContentId($id, $size = 128, $size_h = false) {
		
		global $cms_db_tables;
		
		//return false;		

		/*$function_cache_id = serialize ( $id ) . serialize ( $size ) . serialize ( $size_h );        $function_cache_id = __FUNCTION__ . md5 ( __FUNCTION__ . $function_cache_id );        $cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id );        if (($cache_content) != false) {            //return $cache_content;        }*/
		
		$data = false;
		
		$data ['id'] = $id;
		
		$images = $this->mediaGetForContentId ( $id, $media_type = 'picture' );
		
		if (! empty ( $images )) {
			
			$src = $this->core_model->mediaGetThumbnailForMediaId ( $images ['pictures'] [0] ['id'], $size );
			
			return $src;
		
		}
		
		if (! function_exists ( "imagetypes" ) || ! function_exists ( 'imagecreatefromstring' )) {
			
			exit ( "This PHP installation is not configured with the GD library. Please recompile PHP with GD support to run Microweber. (Neither function imagetypes() nor imagecreatefromstring() does exist)" );
		
		}
		
		if (! extension_loaded ( 'gd' ) && ! function_exists ( 'gd_info' )) {
			
			exit ( 'Please install GD' );
		
		}
		$this->load->library ( 'image_lib' );
		$data = false;
		
		$data ['id'] = $id;
		
		//  $imgages = $this->mediaGetForContentId ( $id, $media_type = 'picture' );		

		$images = array ();
		
		$content = $this->contentGetByIdAndCache ( $id );
		
		$input = ($content ['the_content_body']);
		
		$regexp = "<img\s[^>]*src=(\"??)([^\" >]*?)\\1[^>]*";
		
		$data = $input;
		
		$html = $input;
		
		$images = array ();
		
		preg_match_all ( '/(img|src)\=(\"|\')[^\"\'\>]+/i', $data, $media );
		
		unset ( $data );
		
		$data = preg_replace ( '/(img|src)(\"|\'|\=\"|\=\')(.*)/i', "$3", $media [0] );
		
		foreach ( $data as $url ) {
			
			$info = pathinfo ( $url );
			
			if (isset ( $info ['extension'] )) {
				
				if (($info ['extension'] == 'jpg') || ($info ['extension'] == 'jpeg') || ($info ['extension'] == 'gif') || ($info ['extension'] == 'png')) {
					
					array_push ( $images, $url );
				
				}
			
			}
		
		}
		
		if ($size == 'original') {
			
			return $images [0];
		
		}
		
		if ($size_h == false) {
			
			$size_h = $size;
		
		}
		
		$media_url = $this->core_model->mediaGetUrlDir ();
		
		if (! empty ( $images )) {
			
			$images_test = array ();
			
			$i = 0;
			
			foreach ( $images as $lets_see ) {
				
				if ((stristr ( $lets_see, '.jpg' ) == true) or (stristr ( $lets_see, '.png' ) == true) or (stristr ( $lets_see, '.gif' ) == true) or (stristr ( $lets_see, '.bmp' ) == true) or (stristr ( $lets_see, '.jpeg' ) == true)) {
					
					$images_test [] = $lets_see;
				
				} else {
					
					unset ( $images [$i] );
				
				}
				
				$i ++;
			
			}
			
			$images [0] = $images_test [0];
			
			if ($images [0] != '') {
				
				if (stristr ( $images [0], $media_url ) === FALSE) {
					
					$path_parts = pathinfo ( $images [0] );
					
					$table_content = $cms_db_tables ['table_content'];
					
					$newfilename = $path_parts ['basename'];
					
					$newfilename_path = MEDIAFILES . $newfilename;
					
					$download = $this->core_model->url_getPageToFile ( $images [0], $newfilename_path );
					
					if ($download == true) {
						
						$image_new = $media_url . $newfilename;
						
						$update_body_src = str_ireplace ( site_url (), '{SITE_URL}', $image_new );
						
						//  $update_body_src_escaped =  $this->db->escape ( $update_body_src );						

						//p($update_body_src_escaped,1);						

						$new_body = str_replace ( $images [0], $update_body_src, $content ['content_body'] );
						
						//p($new_body,1);						

						$q = "UPDATE $table_content set content_body= '$new_body' where id ='{$content ['id']}'  ";
						
						$q = $this->core_model->dbQ ( $q );
						
						//var_dump($q);						

						$images [0] = $media_url . $newfilename;
					
					}
					
				//p($path_parts);				

				//var_dump ( $images [0] );				

				//var_Dump ( $media_url, MEDIAFILES );				

				}
			
			}
			
			$test1 = str_ireplace ( $media_url, '', $images [0] );
			
			//var_dump ( $test1 );			

			if (is_file ( MEDIAFILES . $test1 ) == true) {
				
				//$test1				

				$the_original_dir = MEDIAFILES . $test1;
				
				$the_original_dir = dirname ( $the_original_dir );
				
				$the_original_dir = $the_original_dir . '/';
				
				$the_original_dir = reduce_double_slashes ( $the_original_dir );
				
				$origina_filename = str_ireplace ( $the_original_dir, '', MEDIAFILES . $test1 );
				
				$new_filename = $the_original_dir . $size . '_' . $size_h . '/' . $origina_filename;
				
				//$new_filename = str_ireplace (' ', '-',$new_filename );				

				//$new_filename = str_ireplace (' ', '-',$new_filename );				

				mkdir_recursive ( $the_original_dir . $size . '_' . $size_h . '/' );
				
				//var_dump($new_filename);				

				$new_filename_url = $the_original_dir . $size . '_' . $size_h . '/' . $origina_filename;
				
				$new_filename_url = str_ireplace ( MEDIAFILES, $media_url, $new_filename_url );
				
				if (is_file ( $new_filename ) == TRUE) {
					
					$src = $new_filename_url;
				
				} else {
					
					$config ['image_library'] = 'gd2';
					
					$config ['source_image'] = MEDIAFILES . $test1;
					
					$config ['create_thumb'] = false;
					
					$config ['new_image'] = $new_filename;
					
					$config ['maintain_ratio'] = TRUE;
					
					if ($size != 'original') {
						
						$config ['width'] = $size;
						
						if ($size_h != false) {
							
							$config ['height'] = $size_h;
						
						} else {
							
							$config ['height'] = $size;
						
						}
					
					}
					
					$src = $new_filename_url;
					
					$this->image_lib->initialize ( $config );
					
					$this->image_lib->resize ();
				
				}
			
			} else {
				
				$src = ($media_url) . 'pictures/no.gif';
				
				$test1 = str_ireplace ( $media_url, '', $src );
				
				if (is_file ( MEDIAFILES . $test1 ) == true) {
					
					//$test1					

					$the_original_dir = MEDIAFILES . $test1;
					
					$the_original_dir = dirname ( $the_original_dir );
					
					$the_original_dir = $the_original_dir . '/';
					
					$the_original_dir = reduce_double_slashes ( $the_original_dir );
					
					$origina_filename = str_ireplace ( $the_original_dir, '', MEDIAFILES . $test1 );
					
					$new_filename = $the_original_dir . $size . '_' . $size_h . '/' . $origina_filename;
					
					@mkdir ( $the_original_dir . $size . '_' . $size_h . '/' );
					
					//var_dump($new_filename);					

					$new_filename_url = $the_original_dir . $size . '_' . $size_h . '/' . $origina_filename;
					
					$new_filename_url = str_ireplace ( MEDIAFILES, $media_url, $new_filename_url );
					
					if (is_file ( $new_filename ) == TRUE) {
						
						$src = $new_filename_url;
					
					} else {
						
						$config ['image_library'] = 'gd2';
						
						$config ['source_image'] = MEDIAFILES . $test1;
						
						$config ['create_thumb'] = false;
						
						$config ['new_image'] = $new_filename;
						
						$config ['maintain_ratio'] = TRUE;
						
						$config ['width'] = $size;
						
						$config ['height'] = $size_h;
						
						$src = $new_filename_url;
						
						$this->image_lib->initialize ( $config );
						
						$this->image_lib->resize ();
					
					}
				
				}
			
			}
		
		} else {
			
			$src = ($media_url) . 'pictures/no.gif';
			
			$src = ($media_url) . 'pictures/no.gif';
			
			$test1 = str_ireplace ( $media_url, '', $src );
			
			if (is_file ( MEDIAFILES . $test1 ) == true) {
				
				//$test1				

				$the_original_dir = MEDIAFILES . $test1;
				
				$the_original_dir = dirname ( $the_original_dir );
				
				$the_original_dir = $the_original_dir . '/';
				
				$the_original_dir = reduce_double_slashes ( $the_original_dir );
				
				$origina_filename = str_ireplace ( $the_original_dir, '', MEDIAFILES . $test1 );
				
				$new_filename = $the_original_dir . $size . '_' . $size_h . '/' . $origina_filename;
				
				@mkdir ( $the_original_dir . $size . '_' . $size_h . '/' );
				
				//var_dump($new_filename);				

				$new_filename_url = $the_original_dir . $size . '_' . $size_h . '/' . $origina_filename;
				
				$new_filename_url = str_ireplace ( MEDIAFILES, $media_url, $new_filename_url );
				
				if (is_file ( $new_filename ) == TRUE) {
					
					$src = $new_filename_url;
				
				} else {
					
					$config ['image_library'] = 'gd2';
					
					$config ['source_image'] = MEDIAFILES . $test1;
					
					$config ['create_thumb'] = false;
					
					$config ['new_image'] = $new_filename;
					
					$config ['maintain_ratio'] = TRUE;
					
					$config ['width'] = $size;
					
					$config ['height'] = $size_h;
					
					$src = $new_filename_url;
					
					$this->image_lib->initialize ( $config );
					
					$this->image_lib->resize ();
				
				}
			
			}
		
		}
		
		//}		

		$src = str_ireplace ( ' ', '%20', $src );
		
		if ($src != '') {
			
			$this->core_model->cacheWriteAndEncode ( $src, $function_cache_id, $cache_group = 'content' );
			
			return $src;
		
		} else {
			
			return false;
		
		}
	
	}
	
	function contentGetItemPostitionInTheMainCategory($content_id) {
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		$content_id = intval ( $content_id );
		$cache_group = 'content/' . $content_id;
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_content'];
		
		$content = $this->contentGetByIdAndCache ( $content_id );
		
		$q = " select count(*) as qty from $table  where content_parent= '{$content['content_parent']}'  and updated_on < '{$content['updated_on']}' order by updated_on DESC   ";
		
		$q = $this->core_model->dbQuery ( $q );
		
		$q = intval ( $q [0] ['qty'] );
		
		$q = $q + 1;
		
		$this->core_model->cacheWriteAndEncode ( $q, $function_cache_id, $cache_group );
		
		return $q;
	
	}
	
	/**	 * @desc  Cache function for contentGetByIdAndCache	 * @param int	 * @return array	 * @author      Peter Ivanov	 * @version 1.0	 * @since Version 1.0	 */
	
	function contentGetByIdAndCache($id) {
		$id = intval ( $id );
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_group = 'content/' . $id;
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		//  $cache_content = false;		

		if (($cache_content) != false) {
			
			return $cache_content;
		
		} else {
			
			$content = $this->contentGetById ( $id );
			
			$to_cache = $content;
			
			$this->core_model->cacheWriteAndEncode ( $to_cache, $function_cache_id, $cache_group );
			
			return $to_cache;
		
		}
	
	}
	
	/**	 * @desc Function to get single content item by id from the content_table	 * @param int	 * @return array	 * @author      Peter Ivanov	 * @version 1.0	 * @since Version 1.0	 */
	
	function contentGetById($id) {
		global $cms_db_tables;
		$table = $cms_db_tables ['table_content'];
		$id = intval ( $id );
		$q = "SELECT * from $table where id='$id'  limit 0,1 ";
		
		$q = $this->core_model->dbQuery ( $q, __FUNCTION__ . md5 ( $q ), 'content/' . $id );
		$content = $q [0];
		return $content;
	
	}
	
	function contentGetActiveCategoriesForPostIdAndCache($content_id, $starting_from_category_id = false) {
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		$content_id = intval ( $content_id );
		$cache_group = 'content/' . $content_id;
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		} else {
			
			$to_cache = $this->contentGetActiveCategoriesForPostId ( $content_id, $starting_from_category_id );
			
			$this->core_model->cacheWriteAndEncode ( $to_cache, $function_cache_id, $cache_group );
			
			return $to_cache;
		
		}
	
	}
	
	/**	 * @desc Generate unique content URL for post by specifying the id and the titile	 * @param the_id	 * @param the_content_title	 * @return string	 * @author      Peter Ivanov	 * @version 1.0	 * @since Version 1.0	 */
	
	function contentGenerateUniqueUrlTitleFromContentTitle($the_id, $the_content_title, $the_content_url_overide = false) {
		
		global $cms_db_tables;
		
		$this->load->helper ( 'url' );
		
		if ($the_content_url_overide != false) {
			
			$the_content_url_overide = $the_content_url_overide;
		
		}
		
		$table = $cms_db_tables ['table_content'];
		
		$the_content_title = trim ( $the_content_title );
		
		$the_content_title = mb_strtolower ( $the_content_title );
		
		//var_dump($the_content_title);		

		if (function_exists ( 'string_cyr2lat' )) {
			
			$the_content_title = string_cyr2lat ( $the_content_title );
		
		}
		
		$the_id = intval ( $the_id );
		
		if (intval ( $the_id ) != 0) {
			
			$q = "SELECT content_url, content_title from $table where id='{$data_to_save['id']}' ";
			
			$q = $this->core_model->dbQuery ( $q );
			
			$thecontent_url = $q [0] ['content_url'];
			
			$the_new_content_url = url_title ( $the_content_title, 'dash', true );
			
			$the_id = intval ( $the_id );
			
			$q = "SELECT content_url, content_title from $table where content_url='$the_new_content_url' and id!=$the_id limit 0,1 ";
			
			$q = $this->core_model->dbQuery ( $q );
			
			if (! empty ( $q )) {
				
				$the_new_content_url = $the_new_content_url . date ( "ymdhis" );
			
			}
		
		} else {
			
			$the_new_content_url = url_title ( $the_content_title, 'dash', true );
			
			$q = "SELECT content_url, content_title from $table where content_url='$the_new_content_url' limit 0,1 ";
			
			$q = $this->core_model->dbQuery ( $q );
			
			if (! empty ( $q )) {
				
				$the_new_content_url = $the_new_content_url . date ( "ymdhis" );
			
			}
		
		}
		
		return $the_new_content_url;
	
	}
	
	function contentGetActiveCategoriesForPostId($content_id, $starting_from_category_id = false) {
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		$content_id = intval ( $content_id );
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_group = 'content/' . $content_id;
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_content'];
		
		$taxonomy = array ();
		
		$taxonomy ['to_table'] = 'table_content';
		
		$taxonomy ['to_table_id'] = $content_id;
		
		$taxonomy ['taxonomy_type'] = 'category_item';
		
		//$taxonomy ['content_type'] = 'post';		

		$cats = $this->taxonomyGet ( $taxonomy, $orderby = false, $no_limits = true );
		
		//taxonomyGet($data = false, $orderby = false, $no_limits = false)		

		//  var_dump($cats);		

		if (empty ( $cats )) {
			
			return false;
		
		} else {
			
			$return_cats = array ();
			
			if ($starting_from_category_id != false) {
				
				$available_cats = $this->taxonomyGetChildrenItemsIdsRecursiveAndCache ( $starting_from_category_id, $type = 'category' );
				
				if (! empty ( $available_cats )) {
					
					$temp = array ($starting_from_category_id );
					
					$available_cats [] = $starting_from_category_id;
				
				}
			
			} else {
				
				$available_cats = array ();
			
			}
			
			foreach ( $cats as $item ) {
				
				if (empty ( $available_cats )) {
					
					$return_cats [] = $item ['parent_id'];
				
				} else {
					
					$parent = $item ['parent_id'];
					
					if (intval ( $parent ) != 0) {
						
						if (in_array ( $parent, $available_cats )) {
							
							$return_cats [] = $item ['parent_id'];
							
						//$parent = $item ['parent_id'];						

						}
					
					}
				
				}
			
			}
			
			$this->core_model->cacheWriteAndEncode ( $return_cats, $function_cache_id, $cache_group );
			
			return $return_cats;
		
		}
	
	}
	
	function contentActiveCategoriesForPageIdAndCache($page_id, $url, $no_base = false) {
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		$page_id = intval ( $page_id );
		$cache_group = 'content/' . $page_id;
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		} else {
			
			$to_cache = $this->contentActiveCategoriesForPageId2 ( $page_id, $url, $no_base );
			
			$this->core_model->cacheWriteAndEncode ( $to_cache, $function_cache_id, $cache_group );
			
			return $to_cache;
		
		}
	
	}
	
	function contentActiveCategoriesForPageId2($page_id, $url, $no_base = false) {
		
		/*$args = func_get_args ();        foreach ( $args as $k => $v ) {            $function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );        }        $function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );        $cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id );        if (($cache_content) != false) {            if ($cache_content == 'false') {                return false;            } else {                return $cache_content;            }        }*/
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_content'];
		
		$data = array ();
		
		$content = $this->contentGetById ( $page_id );
		
		if ($url != '') {
			
			$url = addslashes ( $url );
		
		}
		
		$table_taxonomy = $cms_db_tables ['table_taxonomy'];
		
		if ($content ['content_subtype'] == 'blog_section') {
			
			$base_category = $content ['content_subtype_value'];
			
			$categories_to_return = array ($base_category );
			
			$pge_url = $content ['content_url'];
			
			//print $pge_url;			

			//exit();			

			if ($pge_url == $url) {
				
				if ($no_base == false) {
					
					$categories_to_return = array ($base_category );
				
				}
			
			} else {
				
				$url_full = site_url ( $url );
				
				//$cat_urls = str_ireplace ( $pge_url, '', $url_full );				

				$cat_urls = $url_full;
				
				//var_dump($cat_urls);				

				$urls = explode ( '/', $cat_urls );
				
				//$categories_to_return = array ();				

				foreacH ( $urls as $item ) {
					
					if ($item != '') {
						
						$item = explode ( ':', $item );
						
						$some_stff = $item;
						
						if (! empty ( $item )) {
							
							if (strtolower ( $item [0] ) == 'category') {
								
								$taxonomy_value = $item [1];
								
								$possible_ids = $this->taxonomyGetTaxonomyIdsForTaxonomyRootIdAndCache ( $base_category, true );
								
								//var_dump ( $possible_ids );								

								//exit ();								

								$data = array ();
								
								$data ['taxonomy_value'] = $taxonomy_value;
								
								$data ['taxonomy_value2'] = rawurldecode ( $taxonomy_value );
								
								$data ['taxonomy_type'] = 'category';
								
								$some_integer = intval ( $data ['taxonomy_value'] );
								
								$q = "Select id from $table_taxonomy where                                (taxonomy_value='{$data ['taxonomy_value']}' or                                taxonomy_value='{$data ['taxonomy_value2']}' or id=$some_integer                                )                                and taxonomy_type='{$data ['taxonomy_type']}'                                ";
								
								$results = $this->core_model->dbQuery ( $q, __FUNCTION__ . md5 ( $q ), 'taxonomy/global' );
								
								//  p($results,1);								

								if (! empty ( $results )) {
									
									foreach ( $results as $res ) {
										
										if (in_array ( $res ["id"], $possible_ids ) == true) {
											
											$categories_to_return [] = $res ["id"];
										
										}
									
									}
								
								}
							
							}
							
							//  p($categories_to_return,1);							

							//							

							$categories_get_from_url = $this->core_model->getParamFromURL ( 'categories' );
							
							if ($categories_get_from_url != false) {
								
								//$taxonomy_value = $item [1];								

								$taxonomy_values = explode ( ',', $categories_get_from_url );
								
								//$categories_to_return = array ();								

								//var_dump ( $possible_ids );								

								//exit ();								

								// var_dump ( $taxonomy_values );								

								if (! empty ( $taxonomy_values )) {
									
									foreach ( $taxonomy_values as $taxonomy_value ) {
										
										$data = array ();
										
										/*$data ['taxonomy_value'] = $taxonomy_value;                                        $data ['taxonomy_type'] = 'category';                                        //  $data ['to_table'] = 'tab';                                        //var_dump($possible_ids);                                        $results = $this->taxonomyGet ( $data );*/
										
										$some_integer = intval ( $taxonomy_value );
										
										$q = "Select id from $table_taxonomy where                                (taxonomy_value='{$taxonomy_value}' or                                taxonomy_value='{$taxonomy_value}' or id=$some_integer                                )                                and taxonomy_type='category'                                ";
										
										// p($q);										

										$results = $this->core_model->dbQuery ( $q, __FUNCTION__ . md5 ( $q ), 'taxonomy/global' );
										
										//var_dump ( $results );										

										if (! empty ( $results )) {
											
											foreach ( $results as $res ) {
												
												//  if (in_array ( $res ["id"], $possible_ids ) == true) {												

												if (intval ( $res ["id"] ) != 0) {
													
													$categories_to_return [] = $res ["id"];
													
												//  $possible_ids1 = $this->taxonomyGetTaxonomyIdsForTaxonomyRootIdAndCache (  $res ["id"], true );												

												//p($possible_ids1);												

												}
												
											//  }											

											}
										
										}
									
									}
								
								}
								
							//}							

							}
						
						}
					
					}
				
				}
			
			}
			
			//exit ( 'asdas' );			

			$categories_to_return = array_unique ( $categories_to_return );
			
			return $categories_to_return;
			
		/*if (! empty ( $categories_to_return )) {                $this->core_model->cacheWriteAndEncode ( $categories_to_return, $function_cache_id );                                return $categories_to_return;            } else {                $this->core_model->cacheWriteAndEncode ( 'false', $function_cache_id );                                return false;            }*/
		
		} else {
			
			//$this->core_model->cacheWriteAndEncode ( 'false', $function_cache_id );			

			return false;
		
		}
	
	}
	
	function contentGetVotesCountForContentId($id, $since_days) {
		
		$qty = $this->votesGetCount ( 'table_content', $id, $since_days = $since_days );
		
		return $qty;
	
	}
	
	function contentActiveCategoriesForPageId($page_id, $url, $no_base = false) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_content'];
		
		$data = array ();
		
		$data ['id'] = $page_id;
		
		$content = $this->getContentAndCache ( $data );
		
		$content = $content [0];
		
		if ($content ['content_subtype'] == 'blog_section') {
			
			$base_category = $content ['content_subtype_value'];
			
			$categories_to_return = array ($base_category );
			
			$pge_url = $this->getContentURLByIdAndCache ( $page_id );
			
			//print $pge_url;			

			if ($pge_url == $url) {
				
				if ($no_base == false) {
					
					$categories_to_return = array ($base_category );
				
				}
			
			} else {
				
				$url_full = site_url ( $url );
				
				$cat_urls = str_ireplace ( $pge_url, '', $url_full );
				
				//var_dump($cat_urls);				

				$urls = explode ( '/', $cat_urls );
				
				foreacH ( $urls as $item ) {
					
					if ($item != '') {
						
						$item = explode ( ':', $item );
						
						if (! empty ( $item )) {
							
							if (strtolower ( $item [0] ) == 'category') {
								
								$taxonomy_value = $item [1];
								
								$possible_ids = $this->taxonomyGetTaxonomyIdsForTaxonomyRootIdAndCache ( $base_category, true );
								
								$data = array ();
								
								$data ['taxonomy_value'] = $taxonomy_value;
								
								//var_dump($possible_ids);								

								$results = $this->taxonomyGetAndCache ( $data );
								
								//  var_dump ( $results );								

								if (! empty ( $results )) {
									
									$categories_to_return = array ();
									
									foreach ( $results as $res ) {
										
										if (in_array ( $res ["id"], $possible_ids ) == true) {
											
											$categories_to_return [] = $res ["id"];
										
										}
									
									}
								
								}
							
							}
						
						}
					
					}
				
				}
			
			}
			
			if (! empty ( $categories_to_return )) {
				
				return $categories_to_return;
			
			} else {
				
				return false;
			
			}
		
		} else {
			
			return false;
		
		}
	
	}
	
	/**	 * Gets content by params	 * if no params are used it will try to get them from the URL	 *	 *	 *	 *	 *	 *	 *	 *	 */
	
	function contentGetByParams($params) {
		//return false;
		//$active_categories2 = $this->content_model->contentActiveCategoriesForPageIdAndCache ( $page ['id'], $url, true );		

		//var_dump ( $params );		

		extract ( $params );
		
		$posts_data = false;
		
		//var_dump($active_categories2);		

		$posts_data ['selected_categories'] = ($params ['selected_categories']);
		
		if (empty ( $posts_data ['selected_categories'] )) {
			
			$categoris_from_url = $this->core_model->getParamFromURL ( 'selected_categories' );
			
			if ($categoris_from_url != '') {
				
				$categoris_from_url = array_trim ( explode ( ',', $categoris_from_url ) );
				$posts_data ['selected_categories'] = $categoris_from_url;
			
			}
		
		}
		if ($_POST ['search_by_keyword'] != '') {
			
			$search_for = $_POST ['search_by_keyword'];
			
			$params ['search_for'] = $_POST ['search_by_keyword'];
		
		}
		
		if ($params ['strict_category_selection'] == false) {
			
			$strict_category_selection = $this->core_model->getParamFromURL ( 'strict_category_selection' );
		
		} else {
		
		}
		
		if ($custom_fields_criteria == false) {
			
			$cf = $this->core_model->getParamFromURL ( 'custom_fields_criteria' );
			
			if ($cf != false) {
				
				$posts_data ['custom_fields_criteria'] = $cf;
			
			}
		
		} else {
			
			$posts_data ['custom_fields_criteria'] = $custom_fields_criteria;
		
		}
		
		if ($params ['search_for'] == false) {
			
			$search_for = $this->core_model->getParamFromURL ( 'keyword' );
		
		}
		
		if ($search_for != '') {
			
			$search_for = html_entity_decode ( $search_for );
			
			$search_for = urldecode ( $search_for );
			
			$search_for = htmlspecialchars_decode ( $search_for );
			
			$posts_data ['search_by_keyword'] = $search_for;
		
		}
		
		if ($params ['is_special'] == false) {
			
			$is_special = $this->core_model->getParamFromURL ( 'is_special' );
			
			if (($is_special == 'y') or ($is_special == 'n')) {
				
				$posts_data ['is_special'] = $is_special;
			
			}
		
		} else {
			
			$posts_data ['is_special'] = $params ['is_special'];
		
		}
		
		if ($content_subtype == false) {
			
			if ($type == false) {
				
				$type = $this->core_model->getParamFromURL ( 'type' );
			
			}
			
			if (trim ( $type ) != '' && trim ( $type ) != 'blog') {
				
				$posts_data ['content_subtype'] = $type;
			
			} else {
				
				$posts_data ['content_subtype'] = 'none';
			
			}
			
			if (trim ( $type ) == 'all') {
				
				unset ( $posts_data ['content_subtype'] );
			
			}
		
		} else {
			
			$posts_data ['content_subtype'] = $content_subtype;
		
		}
		
		if ($typev == false) {
			
			$typev = $this->core_model->getParamFromURL ( 'typev' );
		
		}
		
		if (trim ( $typev ) != '') {
			
			$posts_data ['content_subtype_value'] = $typev;
		
		} else {
			
			$posts_data ['content_subtype_value'] = 'none';
		
		}
		
		if ($content_type == false) {
			
			$content_type = 'post';
		
		}
		
		$posts_data ['content_type'] = $content_type;
		
		if ($items_per_page == false) {
			
			$items_per_page = $this->content_model->optionsGetByKey ( 'default_items_per_page' );
		
		}
		
		$items_per_page = intval ( $items_per_page );
		
		if (empty ( $active_categories2 )) {
			
			$active_categories2 = array ();
		
		}
		
		if ($curent_page == false) {
			
			$curent_page = $this->core_model->getParamFromURL ( 'curent_page' );
		
		}
		
		if (intval ( $curent_page ) < 1) {
			
			$curent_page = 1;
		
		}
		
		if ($commented == false) {
			
			$commented = $this->core_model->getParamFromURL ( 'commented' );
		
		}
		
		if (($timestamp = strtotime ( $commented )) === false) {
			
		//$this->template ['selected_voted'] = false;		

		} else {
			
			$posts_data ['commented'] = $commented;
			
		//$this->template ['selected_voted'] = true;		

		}
		
		if ($voted == false) {
			
			$voted = $this->core_model->getParamFromURL ( 'voted' );
		
		}
		
		if (($timestamp = strtotime ( $voted )) === false) {
		
		} else {
			
			$posts_data ['voted'] = $voted;
		
		}
		
		if ($created_by == false) {
			
			$created_by = $this->core_model->getParamFromURL ( 'author' );
		
		}
		
		//var_dump($tags);		

		if (strval ( $created_by ) != '') {
			
			$posts_data ['created_by'] = $created_by;
		
		} else {
			
		//$this->template ['created_by'] = false;		

		}
		
		$url = uri_string ();
		
		if (stristr ( $url, 'admin/' ) == true) {
		
		} else {
			
			$posts_data ['visible_on_frontend'] = 'y';
		
		}
		
		if (empty ( $orderby ) == false) {
			
			$orderby1 = array ();
			
			$orderby1 [0] = $orderby [0];
			
			$orderby1 [1] = $orderby [1];
		
		} else {
			
			$order = $this->core_model->getParamFromURL ( 'ord' );
			
			$order_direction = $this->core_model->getParamFromURL ( 'ord-dir' );
			
			$orderby1 = array ();
			
			if ($order != false) {
				
				$orderby1 [0] = $order;
			
			} else {
				
				$orderby1 [0] = 'updated_on';
			
			}
			
			if ($order_direction != false) {
				
				$orderby1 [1] = $order_direction;
			
			} else {
				
				$orderby1 [1] = 'DESC';
			
			}
		
		}
		
		$to_return = array ();
		
		$page_start = ($curent_page - 1) * $items_per_page;
		
		$page_end = ($page_start) + $items_per_page;
		
		if (! empty ( $posts_data )) {
			
			if (is_file ( ACTIVE_TEMPLATE_DIR . 'controllers/pre_posts_get.php' )) {
				
				include_once ACTIVE_TEMPLATE_DIR . 'controllers/pre_posts_get.php';
			
			}
			
			//@todo add getContentAndCache, isted of getContent			

			$posts_data ['use_fetch_db_data'] = true;
			
			//                      var_dump($posts_data);			

			$data = $this->getContentAndCache ( $posts_data, $orderby1, array ($page_start, $page_end ), $short_data = false, $only_fields = array ('id', 'content_title', 'content_body', 'content_url', 'content_filename', 'content_parent', 'content_filename_sync_with_editor', 'content_body_filename' ), true );
			
			//      p($data, 1);			

			$to_return ['posts'] = $data;
			
			$posts = $data;
			
			$results_count = $this->getContentAndCache ( $posts_data, $orderby1, $limit = false, $count_only = true, $short_data = true, $only_fields = false );
			
			//var_dump($results_count);			

			$results_count = intval ( $results_count );
			
			$content_pages_count = ceil ( $results_count / $items_per_page );
			
			//var_dump ( $results_count, $items_per_page );			

			$to_return ['posts_pages_count'] = $content_pages_count;
			
			$to_return ['posts_pages_curent_page'] = $curent_page;
			
			//get paging urls			

			$content_pages = $this->pagingPrepareUrls ( false, $content_pages_count );
			
			//var_dump($content_pages);			

			$to_return ['posts_pages_links'] = $content_pages;
			
			return $to_return;
		
		}
	
	}
	
	function contentsAddSiloLinks($content) {
		
		if (! $content || ! is_string ( $content )) {
			
			return;
		
		}
		
		//return;		

		return;
		
		$params = array ();
		
		$params [] = array ('taxonomy_type', 'category' );
		
		//  $params[] =array ('taxonomy_value', 'NULL', '<>', 'and' );		

		$params [] = array ('taxonomy_silo_keywords', '', '<>', 'and' );
		
		$params [] = array ('taxonomy_silo_keywords', 'IS NOT NULL' );
		
		//  $params[] =array ('taxonomy_value', 'IS NOT NULL' );		

		$opts = array ();
		
		$opts ['only_fields'] = array ('id', 'taxonomy_silo_keywords', 'taxonomy_type', 'taxonomy_value' );
		
		$opts ['cache_group'] = 'taxonomy/global';
		
		$opts ['cache'] = true;
		
		$opts ['debug'] = false;
		
		$categories = $this->core_model->fetchDbData ( 'firecms_taxonomy', $params, $opts );
		
		if (empty ( $categories )) {
			
			return $content;
		
		}
		
		//p($categories,1);		

		//return;		

		$this->load->helper ( 'mw_string' );
		
		$siloLinks = array ();
		
		foreach ( $categories as $category ) {
			
			if ($category ['taxonomy_silo_keywords']) {
				
				$siloLink = array ();
				
				$siloLink ['keywords'] = array ();
				
				$siloLink ['url'] = $this->taxonomyGetUrlForTaxonomyIdAndCache ( $category ['id'] );
				
				$siloLink ['id'] = ($category ['id']);
				
				$keywords = explode ( ',', $category ['taxonomy_silo_keywords'] );
				
				if (! empty ( $keywords )) {
					
					foreach ( $keywords as $keyword ) {
						
						$siloLink ['keywords_regexp'] [] = '/ ' . trim ( $keyword ) . ' /e';
						
						$siloLink ['keywords'] [] = trim ( $keyword );
					
					}
					
					$siloLinks [] = $siloLink;
				
				}
			
			}
		
		}
		
		//  p ( $siloLinks, 1 );		

		$linksPerCategory = 1;
		
		$already_replaced = array ();
		
		foreach ( $siloLinks as $siloLink ) {
			
			foreach ( $siloLink ['keywords'] as $kw_for_replace ) {
				
				if (! in_array ( strtolower ( $kw_for_replace ), $already_replaced )) {
					
					$already_replaced [] = strtolower ( $kw_for_replace );
					
					$categoryLink = "<a href='{$siloLink['url']}' title='{$kw_for_replace}'>{$kw_for_replace}</a>";
					
					$content = str_replace_once ( $kw_for_replace, $categoryLink, $content );
				
				}
			
			}
			
		/*            $categoryLink = "' <a href=\"{$siloLink['url']}\">'.trim($0).'</a> '";            $content = preg_replace ( $siloLink ['keywords'], $categoryLink, $content, $linksPerCategory );*/
		
		}
		
		return $content;
	
	}
	
	/**	 * @desc Get latest content from categories that you define + more flexibility	 * @param array	 * @param array	 * @param boolean	 * @return array	 * @author      Peter Ivanov	 * @version 1.0	 * @since Version 1.0	 */
	
	function getContentAndCache($data, $orderby = false, $limit = false, $count_only = false, $short_data = true, $only_fields = false) {
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, 'content/global' );
		
		//$cache_content = false;		

		if (($cache_content) != false) {
			
			if (trim ( strval ( $cache_content ) ) == 'false') {
				
				return false;
			
			} else {
				
				return $cache_content;
			
			}
		
		}
		$only_fields = array ('id' );
		$selected_posts_results = $this->getContent ( $data, $orderby, $limit, $count_only, $short_data, $only_fields );
		//	p($selected_posts_results);
		

		if (! empty ( $selected_posts_results )) {
			
			$this->core_model->cacheWriteAndEncode ( $selected_posts_results, $function_cache_id, 'content/global' );
			
			return $selected_posts_results;
		
		} else {
			
			$this->core_model->cacheWriteAndEncode ( 'false', $function_cache_id, 'content/global' );
		
		}
	
	}
	
	function getContent($data, $orderby = false, $limit = false, $count_only = false, $short_data = false, $only_fields = false) {
		
		global $cms_db_tables;
		
		//print 'getContent params:'.   var_dump($data);		

		//p($data);		

		if ($data ['use_fetch_db_data'] == true) {
			
			$use_fetch_db_data = true;
		
		}
		
		$table = $cms_db_tables ['table_content'];
		
		$table_content = $cms_db_tables ['table_content'];
		
		$table_media = $cms_db_tables ['table_media'];
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $check . $function_cache_id );
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id );
		
		$cache_content = false; //@todo XXX?		

		if (($cache_content) != false) {
			
		//  return $cache_content;		

		}
		
		$table_taxonomy = $cms_db_tables ['table_taxonomy'];
		
		if ($orderby == false) {
			
			$orderby [0] = 'updated_on';
			
			$orderby [1] = 'DESC';
		
		}
		
		if (! empty ( $data ['limit'] )) {
			
			$limit = $data ['limit'];
		
		}
		
		$ids = array ();
		
		;
		
		if (! empty ( $limit )) {
			
			$my_offset = $limit [1] - $limit [0];
			
			$my_limit_q = " limit  {$limit[0]} , $my_offset  ";
		
		} else {
			
			$my_limit_q = false;
		
		}
		
		//exit($my_limit_q);		

		if (($data ['strict_category_selection']) == true) {
			
			$strict_category_selection = true;
		
		} else {
			
			$strict_category_selection = false;
		
		}
		
		unset ( $data ['strict_category_selection'] );
		
		if (! empty ( $data ['selected_categories'] )) {
			
			$categories = $data ['selected_categories'];
			
			$category_ids = array ();
			
			$category_content_ids = array ();
			
			//			

			if (! empty ( $categories )) {
				
				if (is_array ( $categories ) and ! empty ( $categories )) {
					
					//if($strict_category_selection == false){					

					//var_dump($categories);					

					$categories_intvals = array ();
					$categories_count = count ( $categories );
					foreach ( $categories as $item ) {
						
						$item = intval ( $item );
						
						if ($item != 0) {
							
							$categories_intvals [] = $item;
							$category_ids [] = $item;
						}
						
					//}					

					}
					//  p($categories_intvals);					$only_thise_content = $this->taxonomyGetTaxonomyToTableIdsForTaxonomyRootIdAndCache ( $categories_intvals [0] );
					
					//  p($only_thise_content);					

					//var_dump(count ( $categories_intvals ));					//exit;					$only_thise_content_new = array ();
					if (($categories_count) > 1) {
						if (! empty ( $only_thise_content )) {
							
							$categories_intvals_i = implode ( ',', $categories_intvals );
							$categories_intvals_count = count ( $categories_intvals );
							
							$only_thise_content_i = implode ( ',', $only_thise_content );
							/*foreach ( $only_thise_content as $only_thise_content_item ) {                                                                $q = " SELECT parent_id from   $table_taxonomy where                                 parent_id in ({$categories_intvals_i})                        and          content_type='post'                         and to_table_id =$only_thise_content_item                        and to_table ='table_content'                          group by parent_id  ";                                                                                                $q_check1 = $this->core_model->dbQuery ( $q, md5 ( $q ), 'taxonomy' );                                if(count($q_check1) == $categories_intvals_count){                                    $only_thise_content_new [] = $only_thise_content_item;                                }                            }*/
							
							$q = " SELECT to_table_id , count(parent_id) as qty from   $table_taxonomy where                                 parent_id in ({$categories_intvals_i})                        and          content_type='post'                         and to_table_id in ($only_thise_content_i)                        and to_table ='table_content'                          group by to_table_id  ";
							//  var_dump($q);							$q_check1 = $this->core_model->dbQuery ( $q, __FUNCTION__ . md5 ( $q ), 'taxonomy/global' );
							foreach ( $q_check1 as $q_check2 ) {
								if (intval ( $q_check2 ['qty'] ) == intval ( $categories_intvals_count )) {
									$only_thise_content_new [] = $q_check2 ['to_table_id'];
								}
							
							}
							
							$only_thise_content = $only_thise_content_new;
						}
					
					}
					
					$q_check = array ();
					
					if (count ( $categories_intvals > 0 )) {
						
						array_shift ( $categories_intvals );
					
					}
					
					if (! empty ( $categories_intvals )) {
						
						$categories_intvals_implode = implode ( ',', $categories_intvals );
						
						$only_thise_content_q = " parent_id in ($categories_intvals_implode)    and ";
					
					} else {
						
						$only_thise_content_q = false;
					
					}
					$ids_check123 = array ();
					if (! empty ( $only_thise_content )) {
						
						$category_content_ids = $only_thise_content;
						
						$only_thise_content_implode = implode ( ',', $only_thise_content );
						$q = " SELECT to_table_id, parent_id from   $table_taxonomy where  $only_thise_content_q content_type='post'                         and to_table_id in ($only_thise_content_implode)                        and to_table_id is not null group by to_table_id  ";
						$q_check = $this->core_model->dbQuery ( $q, __FUNCTION__ . md5 ( $q ), 'taxonomy/global' );
					} else {
					
					}
					
					if (! empty ( $q_check )) {
						
						$parent_id_items = $q_check;
						
						// var_dump($parent_id_items);						

						//$parent_id_items - array_unique($parent_id_items);						

						foreach ( $parent_id_items as $parent_id_item ) {
							
							if (! in_array ( intval ( $parent_id_item ['to_table_id'] ), $ids )) {
								
								$ids [] = intval ( $parent_id_item ['to_table_id'] );
								
								$category_content_ids [] = intval ( $parent_id_item ['to_table_id'] );
							
							}
							
							if (! in_array ( intval ( $parent_id_item ['parent_id'] ), $category_ids )) {
								
								$category_ids [] = intval ( $parent_id_item ['parent_id'] );
							
							}
						
						}
					
					} else {
						
						if ($use_fetch_db_data == false) {
							
							$ids [] = 'nothing';
							
							$ids [] = 0;
						
						} else {
							
							$ids [] = 0;
						
						}
					
					}
				
				}
			
			}
			
			//p($only_thise_content);			

			//} else {			

			if ($strict_category_selection == true) {
				$strict_ids = array ();
				$categories = array_unique ( $categories );
				$categories_q = (implode ( ',', $categories ));
				
				foreach ( $ids as $id ) {
					
					$q = " select  count(*) as qty from $table_taxonomy where                    to_table= 'table_content'                    and to_table_id= '$id'                    and content_type = 'post'                    and parent_id  IN ($categories_q)                    and taxonomy_type = 'category_item'                    and to_table_id is not null                    group by to_table_id                    ;";
					
					//print $q ;					

					$q = $this->core_model->dbQuery ( $q, __FUNCTION__ . md5 ( $q ), 'taxonomy/global' );
					
					$q = $q [0] ['qty'];
					
					//  print $q ;					

					//  print "<hr>";					

					if ($q == count ( $categories )) {
						
						$strict_ids [] = $id;
					
					} else {
						
						$strict_ids [] = 'No such id';
					
					}
				
				}
				
				if (! empty ( $strict_ids )) {
					
					$ids = $strict_ids;
					
					$category_ids = $strict_ids;
				
				} else {
					
					$ids = false;
				
				}
			
			}
			
		//}		

		}
		
		if (! empty ( $data ['selected_tags'] )) {
			
			$categories = $data ['selected_tags'];
			
			$tag_ids = array ();
			
			foreach ( $categories as $item ) {
				
				if (strval ( $item ) != '') {
					
					$taxonomy_data = array ();
					
					//  var_dump ( $item );					

					$taxonomy_data ['taxonomy_type'] = 'tag';
					
					$taxonomy_data ['to_table'] = 'table_content';
					
					$q = " select to_table_id , id, to_table from $table_taxonomy where                    to_table= '{$taxonomy_data ['to_table']}'                    and content_type = 'post'                    and taxonomy_type = '{$taxonomy_data ['taxonomy_type']}'                    and taxonomy_value = '$item'                    and to_table_id is not null                    group by to_table_id                    ;                    ";
					
					$cache_id = __FUNCTION__ . 'selected_tags' . md5 ( $q );
					
					$cache_id = md5 ( $cache_id );
					
					$q = $this->core_model->dbQuery ( $q, $cache_id, 'taxonomy/global' );
					
					$items = $q;
					
					if (! empty ( $items )) {
						
						foreach ( $items as $the_id ) {
							
							$ids [] = intval ( $the_id ['to_table_id'] );
							
							$tag_ids [] = intval ( $the_id ['to_table_id'] );
						
						}
						
					//lets cleanup unused items					

					/*if (! empty ( $tag_ids )) {                            $category_ids_count = count ( $tag_ids );                            $category_ids_q = implode ( ", ", $tag_ids );                                                        $q = " select count(*) as qty from $table_content where                        id in ($category_ids_q)";                            //var_dump($q);                            $q = $this->core_model->dbQuery ( $q, md5 ( $q ), 'taxonomy' );                            $q = $q [0] ['qty'];                            //  var_dump($q, $category_ids_count);                            //  exit();                            if (intval ( $q ) != $category_ids_count) {                                foreach ( $items as $clean_me ) {                                    $clean_table = $cms_db_tables [$clean_me ['to_table']];                                    $clean_table_id = $clean_me ['to_table_id'];                                    $clean_id = $clean_me ['id'];                                    //var_dump($clean_table, $clean_table_id);                                    $chek = $this->core_model->dbCheckIfIdExistsInTable ( $clean_table, $clean_table_id );                                    //  var_dump($chek);                                    if ($chek == false) {                                        $this->core_model->deleteDataById ( $table_taxonomy, $clean_id, $delete_cache_group = false );                                    }                                }                                $this->core_model->cleanCacheGroup ( 'taxonomy' );                            }                                                }*/
					
					} else {
						
						$ids [] = 'NOTHING FOUND';
						
						$ids [] = 999999999999999999999999999;
						
						$ids [] = 'SOME DUMMY NON EXISTING ID GOES HERE';
						
						$ids [] = 'I AM SURE THERE IS NO SUCH IDS IN ANY DB ON THE WORLD';
					
					}
				
				}
			
			}
		
		}
		
		if (! empty ( $ids )) {
			
			//asort ( $ids );			

			array_unique ( $ids );
			
		//		

		}
		
		if (! empty ( $tag_ids )) {
			
			//asort ( $tag_ids );			

			array_unique ( $tag_ids );
		
		}
		
		if (! empty ( $category_ids )) {
			
			//asort ( $category_ids );			

			array_unique ( $category_ids );
		
		}
		
		/*if ((! empty ( $category_ids )) and (! empty ( $tag_ids ))) {                        $new_ids = array ();                                foreach ( $tag_ids as $id ) {                                if (in_array ( $id, $category_ids ) == true) {                                        $new_ids [] = $id;                                }                        }                        $ids = $new_ids;                }        */
		if ($data ['have_original_link'] == 'y') {
			
			$q = " SELECT id  from   $table_content where  original_link is NOT NULL   and original_link_include_in_advanced_search = 'y' and original_link NOT LIKE ''";
			
			$q = $this->core_model->dbQuery ( $q, md5 ( $q ), 'content/global' );
			
			$the_ids_that_original_link = array ();
			
			foreach ( $q as $item ) {
				
				$the_ids_that_original_link [] = $item ['id'];
			
			}
			
			if (! empty ( $the_ids_that_original_link )) {
				
				if (! empty ( $ids )) {
					
					$new_ids = array ();
					
					foreach ( $ids as $id ) {
						
						if (in_array ( $id, $the_ids_that_original_link ) == true) {
							
							$new_ids [] = $id;
						
						}
					
					}
					
					$ids = $new_ids;
				
				} else {
					
					$ids = $the_ids_that_original_link;
				
				}
			
			}
		
		}
		
		if ($data ['have_videos'] == 'y') {
			
			$ids_imploded = implode ( ',', $ids );
			
			$q = " SELECT id, to_table_id  from   $table_media where  to_table='table_content' and media_type = 'videos' and to_table_id IN ($ids_imploded)  ";
			
			$q = $this->core_model->dbQuery ( $q, md5 ( $q ), 'media' );
			
			$the_ids_that_have_videos = array ();
			
			foreach ( $q as $item ) {
				
				$the_ids_that_have_videos [] = $item ['to_table_id'];
			
			}
			
			if (! empty ( $the_ids_that_have_videos )) {
				
				if (! empty ( $ids )) {
					
					$new_ids = array ();
					
					foreach ( $ids as $id ) {
						
						if (in_array ( $id, $the_ids_that_have_videos ) == true) {
							
							$new_ids [] = $id;
						
						}
					
					}
					
					$ids = $new_ids;
				
				} else {
					
					$ids = $the_ids_that_have_videos;
				
				}
			
			}
		
		}
		
		//search_by_keyword		

		if (trim ( strval ( $data ['search_by_keyword'] ) ) != '') {
			
			$kw = $data ['search_by_keyword'];
			
			//print $kw;			

			$kw_ids = false;
			$kw_ids_q = false;
			
			if (! empty ( $category_ids )) {
				
				$kw_ids = $category_ids;
				$kw_ids_i = implode ( ',', $kw_ids );
				$kw_ids_q = " and id in ($kw_ids_i) ";
			}
			
			$only_in_those_table_fields = array ('content_title', 'content_body', 'content_description' );
			
			//  $keyword_results = $this->core_model->dbRegexpSearch ( $table, $kw, $kw_ids );			

			$kw = html_entity_decode ( $kw );
			
			$kw = urldecode ( $kw );
			
			$kw = htmlspecialchars_decode ( $kw );
			
			//var_dump ( $kw );			

			$the_words_no_explode = ($kw);
			
			//$the_words = explode ( ',', $kw );			$the_words = array ();
			$the_words [0] = $kw;
			
			if (($kw) != '') {
				if (! empty ( $category_content_ids )) {
					
					$category_ids_q = implode ( ',', $category_content_ids );
					
					$category_ids_q = "  id in ($category_ids_q) and ";
				
				} else {
					
					$category_ids_q = false;
				
				}
				
				//var_dump($category_ids_q);				

				/*$search_for_those_kw_in_fetch = $the_words_no_explode;                                $the_words = $this->core_model->addSlashesToArrayAndEncodeHtmlChars ( $the_words );                                $kyworords_q = " SELECT id from $table where ";                                $kyworords_q2 = " SELECT id from $table where ";                                $kyworords_q .= " (( content_title LIKE '%$the_words_no_explode%' ) OR  ";                                $kyworords_q2 .= " (( content_body LIKE '%$the_words_no_explode%' ) OR  ";                                $kyworords_q_1st = " SELECT id from $table where content_title REGEXP '$the_words_no_explode'   $category_ids_q";                                $kyworords_q2_1st = "  SELECT id from $table where content_body REGEXP '$the_words_no_explode'    $category_ids_q";                                //  var_Dump($the_words);                                foreach ( $the_words as $the_word ) {                                        if (trim ( $the_word ) != '') {                                                $kyworords_q .= " ( content_title LIKE '%$the_word%' ) OR  ";                                                $kyworords_q2 .= " ( content_body LIKE '%$the_word%' ) AND  ";                                        }                                }                                if ($kyworords_q != '') {                                        $kyworords_q = '' . $kyworords_q . '  ID is not null  ) OR ';                                }                                if ($kyworords_q2 != '') {                                        $kyworords_q2 = '' . $kyworords_q2 . '  ID is not null )  OR ';                                }                                $kyworords_q .= " ( content_title REGEXP '{$the_words[0]}' )   ";                                $kyworords_q2 .= " ( content_body REGEXP '{$the_words[0]}' )   ";                                $kyworords_q .= " ORDER BY updated_on DESC limit 0,100 ";                                $kyworords_q2 .= " ORDER BY updated_on DESC limit 0,100 ";                                $kyworords_q_1st .= " ORDER BY updated_on DESC limit 0,100 ";                                $kyworords_q2_1st .= " ORDER BY updated_on DESC limit 0,100 ";                                $the_search_q_kw1 = false;                                $the_search_q_kw2 = false;                                foreach ( $the_words as $the_word ) {                                        if (trim ( $the_word ) != '') {                                                $the_search_q_kw1 .= " +*$the_word* ";                                                $the_search_q_kw2 .= "+$the_word ";                                                $the_search_q_kw3 .= "$the_word";                                        }                        //$the_search_q_kw1 = mysql_real_escape_string ( $the_search_q_kw1 );                //$the_search_q_kw2 = mysql_real_escape_string ( $the_search_q_kw2 );                                }                                                                (( content_title LIKE '%$the_search_q_kw3%' or   content_description LIKE '%$the_search_q_kw3%'   or   content_body LIKE '%$the_search_q_kw3%' or content_url LIKE '%$the_search_q_kw3%'  ) )                                                             and ( MATCH content_title   AGAINST ('+".$the_search_q_kw3."' IN BOOLEAN MODE) or   MATCH content_description    AGAINST  ('+".$the_search_q_kw3."' IN BOOLEAN MODE)   or   MATCH content_body   AGAINST   ('+".$the_search_q_kw3."' IN BOOLEAN MODE) or MATCH content_url  AGAINST    ('+".$the_search_q_kw3."' IN BOOLEAN MODE)  )                  */
				$the_search_q_kw3 = $kw;
				$the_search_q = "SELECT idFROM $table_contentwhere $category_ids_q  (( content_title LIKE '%$the_search_q_kw3%' or   content_description LIKE '%$the_search_q_kw3%'   or   content_body LIKE '%$the_search_q_kw3%' or content_url LIKE '%$the_search_q_kw3%'  ) )  and content_type='post'                ";
				/*$the_search_q = "SELECT idFROM `$table_content`where $category_ids_q MATCH (`content_title`,`content_description`, `content_body`, `content_url`) AGAINST ('$the_search_q_kw3' in boolean mode)                 ";*/
				
				/*$the_search_q = "SELECT idFROM $table_contentwhere ( content_title REGEXP '$the_search_q_kw3' or   content_description REGEXP '$the_search_q_kw3'   or   content_body REGEXP '$the_search_q_kw3' or content_url REGEXP '$the_search_q_kw3'  )   ORDER BY id DESC                ";*/
				
				$queries = array ();
				
				$queries [] = $the_search_q;
				
				$result_ids = array ();
				
				foreach ( $queries as $qq ) {
					$qqq = $this->core_model->dbQuery ( $qq, md5 ( $qq ), 'content/global' );
					if (! empty ( $qqq )) {
						foreach ( $qqq as $some_id ) {
							$result_ids [] = $some_id ['id'];
						}
					}
				}
				
				@array_unique ( $result_ids );
				
				$keyword_results = $result_ids;
				
			//p ( $result_ids );			}
			
			if (! empty ( $result_ids )) {
				unset ( $data ['search_by_keyword'] );
				
				foreach ( $result_ids as $keyword_results_i ) {
					$ids_temp [] = $keyword_results_i;
				}
				$ids = $ids_temp;
				
			/*if (! empty ( $category_content_ids )) {                                        $ids_temp = array ();                                                                                $ids = $ids_temp;                                        if (empty ( $ids )) {                                                $ids = false;                                                $ids [] = '0';                                        }                                } else {                                        $ids = $keyword_results;                                }*/
			
			} else {
				
				$ids = false;
				
				$ids [] = '0';
			
			}
		
		}
		
		//		

		//p($ids);		//search_by_rss		

		if (($data ['search_by_is_from_rss'] == 'y') or ($data ['search_by_is_from_rss'] == 'n') or (intval ( $data ['search_by_is_from_rss'] ) != 0)) {
			
			$rss = $data ['search_by_is_from_rss'];
			
			switch ($rss) {
				
				case 'y' :
					
					$data ['is_from_rss'] = 'y';
					
					break;
				
				case 'n' :
					
					$data ['is_from_rss'] = 'n';
					
					break;
				
				default :
					
					if (intval ( $rss ) != 0) {
						
						$data ['rss_feed_id'] = $rss;
					
					} else {
						
					//$data ['rss_feed_id'] = fas;					

					}
					
					break;
			
			}
		
		}
		
		//		

		//var_dump($data);		

		//exit;		

		if (($data ['is_featured'] == 'y') or ($data ['is_featured'] == 'n')) {
		
		} else {
			
			unset ( $data ['is_featured'] );
		
		}
		
		//search by  with_comments		

		if (($data ['with_comments'] == 'y') or ($data ['with_comments'] == 'n')) {
			
			$with_comments = $data ['with_comments'];
			
			if (! empty ( $ids )) {
				
				$the_ids = $ids;
				
				//$the_ids [] = '0';				

				$some_ids = implode ( ',', $the_ids );
				
				$some_ids_not = implode ( ',', $the_ids ); //not used!				

				$some_ids = "  and to_table_id in ($some_ids)  ";
				
				$some_ids_not = "  and to_table_id NOT in ($some_ids_not)  "; //not used!			

			}
			
			switch ($with_comments) {
				
				case 'y' :
					
					$table_comments = $cms_db_tables ['table_comments'];
					
					$q = " SELECT id, to_table_id from $table_comments where to_table = 'table_content'                    $some_ids group by to_table_id  ";
					
					//  var_dump($q);					

					$q = $this->core_model->dbQuery ( $q, md5 ( $q ), 'comments' );
					
					//  var_dump($q);					

					if (! empty ( $q )) {
						
						$only_comments_ids = array ();
						
						foreach ( $q as $itm ) {
							
							$check = true;
							
							//@delete							

							//$check = $this->contentsCheckIfContentExistsById ( $itm ['to_table_id'] );							

							//  var_dump ( $check );							

							if ($check == true) {
								
								$only_comments_ids [] = $itm ['to_table_id'];
							
							} else {
								
								$q1 = "delete from $table_comments where id ={$itm['id']}  ";
								
								$q1 = $this->core_model->dbQ ( $q1 );
							
							}
						
						}
					
					}
					
					$new_ids = array ();
					
					//var_dump($only_comments_ids);					

					//exit;					

					if (! empty ( $only_comments_ids )) {
						
						if (! empty ( $ids )) {
							
							foreach ( $ids as $id ) {
								
								if (in_array ( $id, $only_comments_ids ) == true) {
									
									$new_ids [] = $id;
								
								}
							
							}
							
							$ids = $new_ids;
						
						} else {
							
							$ids = $only_comments_ids;
						
						}
					
					}
					
					//					

					break;
				
				case 'n' :
					
					$table_comments = $cms_db_tables ['table_comments'];
					
					$table_content = $cms_db_tables ['table_content'];
					
					$q = "SELECT id from $table_content where id not in (select to_table_id from $table_comments where to_table = 'table_content' group by to_table_id )                    ";
					
					//					

					$q = $this->core_model->dbQuery ( $q, md5 ( $q ), 'comments' );
					
					//var_dump($q);					

					//exit;					

					if (! empty ( $q )) {
						
						$only_comments_ids = array ();
						
						foreach ( $q as $itm ) {
							
							$only_comments_ids [] = $itm ['id'];
						
						}
					
					}
					
					$new_ids = array ();
					
					if (! empty ( $only_comments_ids )) {
						
						if (! empty ( $ids )) {
							
							foreach ( $ids as $id ) {
								
								if (in_array ( $id, $only_comments_ids ) == true) {
									
									$new_ids [] = $id;
								
								}
							
							}
							
							$ids = $new_ids;
						
						} else {
							
							$ids = $only_comments_ids;
						
						}
					
					}
					
					break;
				
				default :
					
					break;
			
			}
			
		//var_dump ( $ids );		

		//  exit ();		

		} else {
			
			//exit ( '?' );			

			unset ( $data ['with_comments'] );
		
		}
		
		//the voted functionality is built in into $this->core_model->fetchDbData thats why we romove it from here		

		if ($use_fetch_db_data == false) {
			
			//  var_dump($data ['voted']);			

			if (($timestamp = strtotime ( $data ['voted'] )) !== false) {
				
				$voted = strtotime ( $data ['voted'] . ' ago' );
				
				$table_votes = $cms_db_tables ['table_votes'];
				
				$table_content = $cms_db_tables ['table_content'];
				
				//$pastday = date ( 'Y-m-d H:i:s', mktime ( 0, 0, 0, date ( "m" ), date ( "d" ) - $voted, date ( "Y" ) ) );				

				$pastday = date ( 'Y-m-d H:i:s', $voted );
				
				$now = date ( 'Y-m-d H:i:s' );
				
				$q = "SELECT count(to_table_id) as qty, to_table_id from $table_votes where            to_table = 'table_content'            and created_on >'$pastday'            group by to_table_id order by qty desc                    ";
				
				//p ( $q, 1 );				

				$q = $this->core_model->dbQuery ( $q, md5 ( $q ), 'votes' );
				
				$only_voted_ids = array ();
				
				if (! empty ( $q )) {
					
					foreach ( $q as $itm ) {
						
						$only_voted_ids [] = $itm ['to_table_id'];
					
					}
				
				}
				
				$new_ids = array ();
				
				if (! empty ( $only_voted_ids )) {
					
					if (! empty ( $ids )) {
						
						foreach ( $ids as $id ) {
							
							if (in_array ( $id, $only_voted_ids ) == true) {
								
								$new_ids [] = $id;
							
							}
						
						}
						
						$ids = $new_ids;
					
					} else {
						
						$ids = $only_voted_ids;
					
					}
				
				}
			
			} else {
			
			}
		
		}
		
		if (! empty ( $data ['custom_fields_criteria'] )) {
			
			//var_Dump( $data ['custom_fields_criteria'] );			

			$table_custom_fields = $cms_db_tables ['table_custom_fields'];
			
			$table_content = $cms_db_tables ['table_content'];
			
			$only_custom_fieldd_ids = array ();
			
			$use_fetch_db_data = true;
			
			$ids_q = "";
			
			if (! empty ( $ids )) {
				
				$ids_i = implode ( ',', $ids );
				
				$ids_q = " and to_table_id in ($ids_i) ";
				
			/*foreach ( $ids as $id ) {                    if (in_array ( $category_content_ids, $id )) {                                        }                    var_dump($id);                }*/
			
			}
			
			//var_dump($ids);			

			//var_dump($category_content_ids);			

			$only_custom_fieldd_ids = array ();
			
			foreach ( $data ['custom_fields_criteria'] as $k => $v ) {
				
				if (is_array ( $v ) == false) {
					
					$v = addslashes ( $v );
				
				}
				
				$k = addslashes ( $k );
				
				if (! empty ( $category_content_ids )) {
					
					$category_ids_q = implode ( ',', $category_content_ids );
					
					$category_ids_q = " and to_table_id in ($category_ids_q) ";
				
				} else {
					
					$category_ids_q = false;
				
				}
				
				$only_custom_fieldd_ids_q = false;
				
				if (! empty ( $only_custom_fieldd_ids )) {
					
					$only_custom_fieldd_ids_i = implode ( ',', $only_custom_fieldd_ids );
					
					$only_custom_fieldd_ids_q = " and to_table_id in ($only_custom_fieldd_ids_i) ";
				
				}
				
				$q = "SELECT  to_table_id from $table_custom_fields where            to_table = 'table_content' and            custom_field_name = '$k' and            custom_field_value = '$v'   $ids_q   $only_custom_fieldd_ids_q                                  ";
				
				$q2 = $q;
				
				// var_dump($q);				

				$q = $this->core_model->dbQuery ( $q, md5 ( $q ), 'custom_fields' );
				
				//var_dump($q); 				

				if (! empty ( $q )) {
					
					$ids_old = $ids;
					
					$ids = array ();
					
					foreach ( $q as $itm ) {
						
						$only_custom_fieldd_ids [] = $itm ['to_table_id'];
						
						//  if(in_array($itm ['to_table_id'],$category_ids)== false){						

						$ids [] = $itm ['to_table_id'];
						
					//  }					

					//					

					}
					
				//var_dump ( $ids );				

				//var_dump($ids_old);				

				} else {
					
					//  $ids = array();					

					$remove_all_ids = true;
					
					$ids = false;
					
					$ids [] = '0';
					
					$ids [] = 0;
					
				//  var_dump($q);				

				}
			
			}
			
		/*$new_ids = array ();            if (! empty ( $only_custom_fieldd_ids )) {                if (! empty ( $only_custom_fieldd_ids )) {                    foreach ( $only_custom_fieldd_ids as $id ) {                        //if (in_array ( $id, $only_custom_fieldd_ids ) == true) {                            $new_ids [] = $id;                        //}                    }                    $ids = $new_ids;                } else {                    $ids = $only_custom_fieldd_ids;                }            }*/
		
		//var_dump($ids);		

		//exit;		

		}
		
		if ($remove_all_ids == true) {
			
			$ids = false;
			
			$ids [] = '0';
		
		}
		
		if (! empty ( $ids )) {
			
			$ids = array_unique ( $ids );
			$those_ids = $ids;
		}
		
		$flds = $only_fields;
		
		//save is get!!!		

		/*$this_function_cache_id = 'content_get_the_ids' . $function_cache_id . md5 ( serialize ( $flds ) );        $this_function_cache_content = $this->core_model->cacheGetContentAndDecode ( $this_function_cache_id );        if (($this_function_cache_content) != false) {        $save = $this_function_cache_content;        } else {        $save = $this->core_model->getDbData ( $table = $table, $criteria = $data, $limit, $offset = false, $orderby = $orderby, $cache_group = 'content', $debug = false, $ids = $ids, $count_only = $count_only, $flds );        $this->core_model->cacheWriteAndEncode ( $save, $this_function_cache_id, $cache_group = 'content' );        }*/
		
		if (! empty ( $data ['exclude_ids'] )) {
			
			$exclude_ids = $data ['exclude_ids'];
		
		} else {
			
			$exclude_ids = false;
		
		}
		
		//  var_dump($data, $limit);		

		//function getDbData($table = false, $criteria = false, $limit = false, $offset = false, $orderby = false, $cache_group = false, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = false) {		

		//$use_fetch_db_data = true;		

		//var_dump($data ['use_fetch_db_data']);		

		//exit;		

		if ($use_fetch_db_data == false) {
			//print '---------';			/*      var_dump($those_ids);            var_dump($ids);                var_dump($category_content_ids);*/
			$flds = array ('id' );
			$data ['search_by_keyword_in_fields'] = $only_in_those_table_fields;
			
			$save = $this->core_model->getDbData ( $table = $table, $criteria = $data, $limit, $offset = false, $orderby = $orderby, $cache_group = 'content/global', $debug = false, $ids = $ids, $count_only = $count_only, $flds, $exclude_ids, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = $short_data );
			
		//var_dump($save);		

		} else {
			
			$criteria = array ();
			
			foreach ( $data as $k => $v ) {
				
				$criteria [] = array ($k, $v );
			
			}
			
			$deb = false; //debug
			

			$db_opt = array ();
			
			//$db_opt ['only_fields'] = $flds;
			$db_opt ['only_fields'] = array ('id' );
			
			$db_opt ['include_ids'] = $ids;
			
			$db_opt ['exclude_ids'] = $exclude_ids;
			
			$db_opt ['limit'] = $limit;
			
			$db_opt ['get_count'] = $count_only;
			
			$db_opt ['search_keyword'] = $search_for_those_kw_in_fetch;
			
			$db_opt ['search_keyword_only_in_those_fields'] = $only_in_those_table_fields;
			
			$db_opt ['debug'] = false;
			
			$db_opt ['cache_group'] = 'content/global';
			
			$db_opt ['order'] = $orderby;
			
			//$aOptions ['search_keyword_only_in_those_fields'] = array('id', 'content_body');			

			//$only_in_those_table_fields;			

			/*  $save = $this->core_model->fetchDbData ( $table, $criteria, array ('only_fields' => $flds, 'include_ids' => $ids, 'exclude_ids' => $exclude_ids,            'limit' => $limit, 'get_count' => $count_only, 'search_keyword' => $search_for_those_kw_in_fetch,            'debug' => $deb, 'cache_group' => 'content', 'order' => $orderby ) );*/
			
			//p($db_opt);			

			$save = $this->core_model->fetchDbData ( $table, $criteria, $db_opt );
		
		}
		
		if ($count_only == true) {
			
			//$this->core_model->cacheWriteAndEncode ( $save, $function_cache_id, $cache_group = 'content' );			

			return $save;
		
		}
		
		$media_url = $this->core_model->mediaGetUrlDir ();
		
		$return = array ();
		
		if (! empty ( $save )) {
			$full_items = array ();
			foreach ( $save as $item ) {
				$full_items [] = $this->contentGetById ( $item ['id'] );
			}
			$save = $full_items;
		}
		
		if (! empty ( $save )) {
			
			foreach ( $save as $item ) {
				
				$relations = array ();
				
				$content_layout = false;
				
				$content_style = false;
				
				$content_layout = trim ( $item ['content_layout_name'] );
				
				$content_style = trim ( $item ['content_layout_style'] );
				
				$content_layout_url_replace = false;
				
				if (trim ( $content_layout ) != '') {
					
					if (is_dir ( TEMPLATE_DIR . 'layouts/' . $content_layout )) {
						
						$content_layout_url_replace = TEMPLATE_URL . 'layouts/' . $content_layout . '/';
					
					}
				
				}
				
				$content_style_url_replace = false;
				
				if (trim ( $content_style ) != '') {
					
					//print  TEMPLATE_DIR . 'layouts/' . $content_layout . '/styles/' . $content_style;					

					$content_style_file = false;
					
					$content_style_file = TEMPLATE_DIR . 'layouts/' . $content_layout . '/styles/' . $content_style;
					
					if (is_file ( $content_style_file )) {
						
						$content_style_url_replace = TEMPLATE_URL . 'layouts/' . $content_layout . '/styles/' . $content_style;
						
						//p($content_style_url_replace);						

						$content_style_url_replace = str_ireplace ( '.css', '/', $content_style_url_replace );
					
					}
				
				}
				
				foreach ( $item as $k => $v ) {
					
					if (is_array ( $v ) == false) {
						
						if (trim ( strval ( $v ) ) != '') {
							
							$desc = ($v);
							
							$desc = str_ireplace ( '{MEDIAURL}', $media_url, $desc );
							
							$desc = str_ireplace ( '{SITEURL}', site_url (), $desc );
							
							//$item [$k] = $desc;							

							if ($content_layout_url_replace != false) {
								
								$desc = str_ireplace ( '{LAYOUTURL}', $content_layout_url_replace, $desc );
								
								$desc = str_ireplace ( '{LAYOUT_URL}', $content_layout_url_replace, $desc );
							
							}
							
							if ($content_style_url_replace != false) {
								
								$desc = str_ireplace ( '{STYLEURL}', $content_style_url_replace, $desc );
								
								$desc = str_ireplace ( '{STYLE_URL}', $content_style_url_replace, $desc );
							
							}
							
							$v = $desc;
						
						}
						
						if (strstr ( $v, '<microweber>' ) == true) {
							
							//exit($v);							

							$item ['dynamic_content_relations'] = array ('asd' );
							
							preg_match_all ( '|<microweber.*?</microweber>|ms', $v, $matches );
							
							if (! empty ( $matches )) {
								
								foreach ( $matches as $m ) {
									
									//p ( $m );									

									if (! empty ( $m )) {
										
										foreach ( $m as $n ) {
											
											if (trim ( $n ) != '') {
												
												//												

												$n = str_replace ( '<microweber>', '', $n );
												
												$n = str_replace ( '</microweber>', '', $n );
												
												$n = json_decode ( $n, 1 );
												
												$exist = false;
												
												//  $m = json_decode ( $m );												

												foreach ( $relations as $re => $rel ) {
													
													if ($rel == $n) {
														
														$exist = true;
														
													//  var_dump ( $n );													

													}
												
												}
												
												if ($exist == false) {
													
													$relations [] = $n;
												
												}
												
											//											

											}
										
										}
									
									}
								
								}
								
								$item ['dynamic_content_relations'] = $relations;
							
							}
							
						//p($matches, 0);						

						}
						
						if ($k == 'id') {
							
							$media = $this->core_model->mediaGet ( 'table_content', $v );
							
							if (! empty ( $media )) {
								
								//var_dump($media);								

								$item ['media'] = $media;
							
							} else {
								
								$item ['media'] = false;
							
							}
						
						}
						
						if ($k == 'content_body') {
							
							if (is_array ( $v ) == false) {
								
								/*if ($item ['content_filename_sync_with_editor'] == 'y') {                                    if (trim ( $item ['content_filename'] ) != '') {                                        $the_active_site_template = $this->content_model->optionsGetByKey ( 'curent_template' );                                        $the_active_site_template_dir = TEMPLATEFILES . $the_active_site_template . '/';                                        if (is_file ( $the_active_site_template_dir . $item ['content_filename'] ) == true) {                                            {                                                //  var_dump ( $the_active_site_template_dir );                                            //$v = $the_active_site_template_dir;                                            //$v = file_get_contents ( $the_active_site_template_dir . $item ['content_filename'] );                                            //print $v;                                            //exit;                                            }                                        }                                    }                                }*/
								
								if ($item ['content_body_filename'] != false) {
									
									if (trim ( $item ['content_body_filename'] ) != '') {
										
										$the_active_site_template = $this->content_model->optionsGetByKey ( 'curent_template' );
										
										$the_active_site_template_dir = TEMPLATEFILES . $the_active_site_template . '/content_files/';
										
										if (is_file ( $the_active_site_template_dir . $item ['content_body_filename'] ) == true) {
											
											{
												
												//$v = file_get_contents ( $the_active_site_template_dir . $item ['content_body_filename'] );												

												//  $v = html_entity_decode ( $v );												

												//$v = htmlspecialchars_decode ( $v );												

												$this->load->vars ( $this->template );
												
												$content_filename1 = $this->load->file ( $the_active_site_template_dir . $item ['content_body_filename'], true );
												
												$v = $content_filename1;
											
											}
										
										}
									
									}
								
								}
								
								if ($v != '') {
									
									if (trim ( $item ['content_body_filename'] ) == '') {
										
										$desc = htmlspecialchars_decode ( $v, ENT_QUOTES );
									
									} else {
										
										$desc = ($v);
									
									}
									
									$desc = str_ireplace ( '{MEDIAURL}', $media_url, $desc );
									
									$desc = str_ireplace ( '{SITEURL}', site_url (), $desc );
									
									if ($somepos !== false) {
										
									//$desc = substr ( $desc, 0, $somepos );									

									}
									
									//  $desc = 'aaaa';									

									$item ['the_content_body'] = $desc;
									
									// Silo linking									

									// This field is used only for content visualization and it's not used by admin panel									

									if ($this->optionsGetByKey ( 'enable_silo_linking' )) {
										
										$item ['the_content_body'] = $this->contentsAddSiloLinks ( $item ['the_content_body'] );
									
									}
									
									$desc = htmlspecialchars_decode ( $v, ENT_QUOTES );
									
									$desc = strip_tags ( $desc );
									
									$desc = str_ireplace ( '{MEDIAURL}', $media_url, $desc );
									
									$desc = str_ireplace ( '{SITEURL}', site_url (), $desc );
									
									$item ['content_body_nohtml'] = $desc;
								
								}
							
							}
						
						}
						
						if ($k == 'content_title') {
							
							if ($v != '') {
								
								if (is_array ( $v ) == false) {
									
									$desc = htmlspecialchars_decode ( $v, ENT_QUOTES );
									
									$desc = strip_tags ( $desc );
									
									$desc = str_ireplace ( '{MEDIAURL}', $media_url, $desc );
									
									$desc = str_ireplace ( '{SITEURL}', site_url (), $desc );
									
									$item ['content_title_nohtml'] = $desc;
								
								}
							
							}
						
						}
						
						//put links						

						if (is_array ( $v ) == false) {
							
							if (trim ( strval ( $v ) ) != '') {
								
								$v = str_ireplace ( '{MEDIAURL}', $media_url, $v );
								
								$v = str_ireplace ( '{SITEURL}', site_url (), $v );
							
							}
						
						}
					
					}
					
					$item [$k] = $v;
				
				}
				
				$return [] = $item;
			
			}
			
			$return2 = array ();
			
			foreach ( $return as $item ) {
				
				$item2 = array ();
				
				foreach ( $item as $k => $v ) {
					
					if ($k == 'custom_fields') {
						
						if (is_array ( $v ) == true) {
							
							if (empty ( $v ) == false) {
								
								$custom_fields = $v;
								
								$v2 = array ();
								
								foreach ( $custom_fields as $cfk => $cfv ) {
									
									if (is_array ( $cfv ) == false) {
										
										$cfv = str_ireplace ( '{MEDIAURL}', $media_url, $cfv );
										
										$cfv = str_ireplace ( '{SITEURL}', site_url (), $cfv );
										
										if ($content_layout_url_replace != false) {
											
											$cfv = str_ireplace ( '{LAYOUTURL}', $content_layout_url_replace, $cfv );
											
											$cfv = str_ireplace ( '{LAYOUT_URL}', $content_layout_url_replace, $cfv );
										
										}
										
										if ($content_style_url_replace != false) {
											
											$cfv = str_ireplace ( '{STYLEURL}', $content_style_url_replace, $cfv );
											
											$cfv = str_ireplace ( '{STYLE_URL}', $content_style_url_replace, $cfv );
										
										}
										
										//  var_dump ( $cfv );										

										$v2 [$cfk] = $cfv;
									
									}
								
								}
								
								$v = $v2;
							
							}
						
						}
					
					}
					
					//var_dump($k);					

					$item2 [$k] = $v;
				
				}
				
				$return2 [] = $item2;
			
			}
			
			return $return2;
		
		} else {
			
			return false;
		
		}
	
	}
	
	function pagingPrepareUrls($base_url = false, $pages_count) {
		
		//getCurentURL()		

		if ($base_url == false) {
			
			$base_url = getCurentURL ();
		
		}
		
		//  print $base_url;		

		$page_links = array ();
		
		$the_url = parse_url ( $base_url, PHP_URL_QUERY );
		
		$the_url = $base_url;
		
		$the_url = explode ( '/', $the_url );
		
		//var_dump ( $the_url );		

		for($x = 1; $x <= $pages_count; $x ++) {
			
			$new_url = array ();
			
			$new = array ();
			
			foreach ( $the_url as $itm ) {
				
				$itm = explode ( ':', $itm );
				
				if ($itm [0] == 'curent_page') {
					
					$itm [1] = $x;
				
				}
				
				$new [] = implode ( ':', $itm );
			
			}
			
			$new_url = implode ( '/', $new );
			
			//var_dump ( $new_url);			

			$page_links [$x] = $new_url;
		
		}
		
		for($x = 1; $x <= count ( $page_links ); $x ++) {
			
			if (stristr ( $page_links [$x], 'curent_page:' ) == false) {
				
				$page_links [$x] = reduce_double_slashes ( $page_links [$x] . '/curent_page:' . $x );
			
			}
		
		}
		
		//var_dump($page_links);		

		return $page_links;
	
	}
	
	function getBlogSections() {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_content'];
		
		//var_dump($_GLOBALS ['table_content'] );		

		//exit;		

		$orderby [0] = 'updated_on';
		
		$orderby [1] = 'DESC';
		
		$data = array ();
		
		$data ['content_subtype'] = 'blog_section';
		
		$data ['content_type'] = 'page';
		
		$get = $this->core_model->getDbData ( $table = $table, $criteria = $data, $limit = false, $offset = false, $orderby = $orderby, $cache_group = 'content', $debug = false, $ids = false );
		
		//var_dump($save);		

		return $get;
	
	}
	
	function applyGlobalTemplateReplaceables($content, $replaceables = false) {
		
		//return false;		

		$content_meta_title = $this->content_model->optionsGetByKey ( 'content_meta_title' );
		
		$content_meta_description = $this->content_model->optionsGetByKey ( 'content_meta_description' );
		
		$content_meta_keywords = $this->content_model->optionsGetByKey ( 'content_meta_keywords' );
		
		$content_meta_other_code = $this->content_model->optionsGetByKey ( 'content_meta_other_code' );
		
		$global_replaceables = array ();
		
		$global_replaceables ["content_meta_title"] = $content_meta_title;
		
		$global_replaceables ["content_meta_description"] = $content_meta_description;
		
		$global_replaceables ["content_meta_keywords"] = $content_meta_keywords;
		
		$global_replaceables ["content_meta_other_code"] = $content_meta_other_code;
		
		$content_to_return = false;
		
		foreach ( $global_replaceables as $k => $v ) {
			
			//if (array_key_exists ( $k, $replaceables ) == true) {			

			$v = $replaceables [$k];
			
			if (strval ( $v ) == '') {
				
				$v = $global_replaceables [$k];
			
			}
			
			//print $k;			

			$content = str_ireplace ( '{' . $k . '}', $v, $content );
			
		//}		

		}
		
		//$content = preg_replace ( '/<!--(.|\s)*?-->/', '', $content );		

		//$content =    preg_replace('<%DIV%[^>]*>(.*?)</%DIV                                                                                                                                                                                                                                                                                                                       								%>', '', $content);		

		//  $content = preg_replace('#{(?!div|div)[a-z0-9]+}#is', '$1', $content);		

		//$search = array ('/\[b\](.*?)\[\/b\]/is', '/\[i\](.*?)\[\/i\]/is', '/\[u\](.*?)\[\/u\]/is', '/\[img\](.*?)\[\/img\]/is', '/\[url\](.*?)\[\/url\]/is', '/\[url\=(.*?)\](.*?)\[\/url\]/is' );		

		//$replace = array ('<strong>$1</strong>', '<em>$1</em>', '<u>$1</u>', '<img src="$1" />', '<a href="$1">$1</a>', '<a href="$1">$2</a>' );		

		//$content = preg_replace ( $search, $replace, $content );		

		/*$content1  = eregi("<div class=\"remove-on-submit\">(.*)</div>",$content,$regs);        if(strval($regs[1]) != ''){            $search = "<div class=\"remove-on-submit\">".$regs[1]."</div>";            $content = str_replace($search, '', $content);        }*/
		
		$content = str_ireplace ( '{SITEURL}', site_url (), $content );
		
		$content = str_ireplace ( '{SITE_URL}', site_url (), $content );
		
		$content = str_ireplace ( '{JS_API_URL}', site_url ( 'api/js' ) . '/', $content );
		
		$content = str_ireplace ( '{API_URL}', site_url ( 'api' ) . '/', $content );
		
		return $content;
		
	//$content = htmlGetTagFromHtmlString('class', 'box-bottom',$content );	

	/*        return $content;        //require_once 'htmlsql-v0.5/htmlsql.class.php';        //require_once ("htmlsql-v0.5/snoopy.class.php");        $wsql = new htmlsql ( );        $html = $content;        $layout = $html;        // connect to a string        if (! $wsql->connect ( 'string', $html )) {            return $layout;        }        //if (! $wsql->query ( 'SELECT * FROM to_table  ' )) {        //if (! $wsql->query ( 'SELECT * FROM microweber ' )) {        if (! $wsql->query ( 'SELECT * FROM * where preg_match("/^box-bottom/i", $class)  ' )) {        //  print "Query error: " . $wsql->error;       // exit;        return $layout;        }        $arr = $wsql->fetch_array ();        if (! empty ( $arr )) {            foreach ( $arr as $row ) {                    //$result = json_decode ( $json, 1 );                    p ( $row , 0);            }        }*/
	
	//  return $html;	

	//return $content;	

	}
	
	//this funtion is used in the backend (admin) its diferent from the one in templates model	

	function getLayoutFiles() {
		
		$this->load->helper ( 'directory' );
		
		//$path = BASEPATH . 'content/templates/';		

		$the_active_site_template = $this->optionsGetByKey ( 'curent_template' );
		
		$path = TEMPLATEFILES . '' . $the_active_site_template . '/';
		
		//  print $path;		

		//exit;		

		$map = directory_map ( $path, TRUE );
		
		$to_return = array ();
		
		foreach ( $map as $filename ) {
			
			if (stristr ( $filename, '.php' ) == true) {
				
				$fin = @file_get_contents ( $path . $filename );
				
				$to_return_temp = array ();
				
				$to_return_temp ['filename'] = $filename;
				
				if (preg_match ( '/description:.+/', $fin, $regs )) {
					
					$result = $regs [0];
					
					$result = str_ireplace ( 'description:', '', $result );
					
					$to_return_temp ['description'] = trim ( $result );
				
				}
				
				if (preg_match ( '/name:.+/', $fin, $regs )) {
					
					$result = $regs [0];
					
					$result = str_ireplace ( 'name:', '', $result );
					
					$to_return_temp ['name'] = trim ( $result );
				
				}
				
				if (preg_match ( '/type:.+/', $fin, $regs )) {
					
					$result = $regs [0];
					
					$result = str_ireplace ( 'type:', '', $result );
					
					$to_return_temp ['type'] = trim ( $result );
				
				}
				
				if ($to_return_temp ['type'] == 'layout') {
					
					$to_return [] = $to_return_temp;
				
				}
			
			}
		
		}
		
		return $to_return;
	
	}
	
	function saveMenu($data) {
		
		if (empty ( $data )) {
			
			return false;
		
		}
		
		if ($data ['item_parent'] != 0 and intval ( $data ['content_id'] ) != 0) {
			
			$check_if_exists = array ();
			
			$check_if_exists ['item_parent'] = $data ['item_parent'];
			
			$check_if_exists ['content_id'] = $data ['content_id'];
			
			$table = TABLE_PREFIX . 'menus';
			
			$check = $this->core_model->getDbData ( $table, $check_if_exists, $limit = false, $offset = false, $orderby, $cache_group = 'menus' );
			
			$check = $check [0];
			
			if (! empty ( $check )) {
				
				$data ['id'] = $check ['id'];
			
			}
			
			if (intval ( $check ['position'] ) == 0) {
				
				//find position				

				$sql = "SELECT max(position) as maxpos from $table where item_parent='{$data ['item_parent']}'  ";
				
				$q = $this->core_model->sqlQuery ( $sql, 'menus' );
				
				$result = $q [0];
				
				$maxpos = intval ( $result ['maxpos'] ) + 1;
				
				//var_dump($maxpos);				

				$data ['position'] = $maxpos;
				
			//exit;			

			}
		
		}
		
		//var_dump ( $data );		

		//  exit ();		

		$data_to_save = $data;
		
		$data_to_save ['cache_group'] = 'menus';
		
		$data_to_save ['model_group'] = strtolower ( get_class () );
		
		if ($data_to_save ['menu_url']) {
			
			require_once 'Zend/Uri.php';
			
			require_once 'Zend/Uri/Http.php';
			
			$valid = Zend_Uri::check ( $data_to_save ['menu_url'] );
			
			if ($valid == false) {
				
				unset ( $data_to_save ['menu_url'] );
			
			} else {
			
			}
			
			$data_to_save ['item_type'] = $data ['item_type'];
		
		}
		
		$data_to_save_options = array ();
		
		$data_to_save_options ['delete_cache_groups'] = array ('menus' );
		
		$table = TABLE_PREFIX . 'menus';
		
		$save = $this->core_model->saveData ( $table, $data_to_save );
		
		$this->core_model->cleanCacheGroup ( 'menus' );
		
		$this->fixMenusPositions ();
		
		//var_dump ( $data_to_save );		

		//exit ();		

		return $save;
	
	}
	
	function getMenus($data = false, $orderby = false) {
		
		$table = TABLE_PREFIX . 'menus';
		
		if ($orderby == false) {
			
			$orderby [0] = 'updated_on';
			
			$orderby [1] = 'DESC';
		
		}
		
		$save = $this->core_model->getDbData ( $table, $data, $limit = false, $offset = false, $orderby, $cache_group = 'menus' );
		
		return $save;
	
	}
	
	function getBreadcrumbsByURLAndPrintThem($the_url = false, $include_home = true) {
		
		//return false;		

		$quick_nav = $this->getBreadcrumbsByURLAsArray ( $the_url, $include_home );
		
		?>        <?php
		if (! empty ( $quick_nav )) {
			
			?>

<ul class="breadcrumb">                     <?php
			foreach ( $quick_nav as $item ) {
				
				?>                        <li><a
        href="<?php
				print $item ['url'];
				?>"><?php
				print ucwords ( $item ['title'] );
				?></a></li>





<?php
			}
			print '</ul>';
		}
	}
	function getBreadcrumbsByURLAsArray($the_url = false, $include_home = true, $options = array()) {
		
		if ($the_url != false) {
			
		//$the_url = $the_url;		

		} else {
			
			$the_url = getCurentURL ();
		
		}
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $the_url . $function_cache_id );
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		if (is_array ( $the_url )) {
			
			$the_page = $the_url;
		
		} else {
			
			$the_pages_nav = array ();
			
			$the_nav = array ();
			
			$the_page = $this->getContentByURL ( $the_url );
			
		//var_dump($the_page);		

		}
		
		$the_page_full = $the_page;
		
		$the_pages_nav [] = $the_page ['id'];
		
		$the_page = $the_page ['id'];
		
		if (intval ( $the_page ) != 0) {
			
			if ($the_page_full ['content_type'] == 'post') {
				
				$parent_ids = $this->getParentPagesIdsForPageIdAndCache ( $the_page );
			
			} else {
				
				$parent_ids = $this->getParentPagesIdsForPageIdAndCache ( $the_page );
				
				if (! empty ( $parent_ids )) {
					
					foreach ( $parent_ids as $item ) {
						
						$the_pages_nav [] = $item;
					
					}
				
				}
			
			}
		
		}
		
		/*  $parent_ids = $this->getParentPagesIdsForPageIdAndCache ( $the_page );        if (! empty ( $parent_ids )) {            foreach ( $parent_ids as $item ) {                $the_pages_nav [] = $item;            }        }        */
		
		$home_page = $this->content_model->getContentHomepage ();
		
		$home_page_url = $this->getContentURLByIdAndCache ( $home_page ['id'] );
		
		if ($include_home == true) {
			
			$temp = array ();
			
			$temp ['content_id'] = $home_page ['id'];
			
			$temp ['title'] = $home_page ['content_title'];
			
			$temp ['url'] = $home_page_url;
			
			$the_nav [] = $temp;
		
		}
		
		$active_categories_for_nav = $this->content_model->contentActiveCategoriesForPageIdAndCache ( $the_page, $the_url );
		
		//var_dump($active_categories2);   		

		//var_dump($parent_ids);		

		if (! empty ( $the_pages_nav )) {
			
			$the_pages_nav = array_reverse ( $the_pages_nav );
			
			$categories_already_shown = array ();
			
			$titles_already_shown = array ();
			
			foreach ( $the_pages_nav as $item ) {
				
				$item = intval ( $item );
				
				$home_id = intval ( $home_page ['id'] );
				
				if ($item != $home_id) {
					
					if ($item != 0) {
						
						$page = array ();
						
						$page = $this->contentGetByIdAndCache ( $item );
						
						if ($page ['content_type'] == 'page') {
							
							$page_url = $this->getContentURLByIdAndCache ( $item );
						
						} else {
							
							$page_url = $this->contentGetHrefForPostId ( $item );
						
						}
						
						$temp = array ();
						
						$temp ['content_id'] = $item;
						
						$temp ['title'] = $page ['content_title'];
						
						$temp ['url'] = $page_url;
						
						if (intval ( $page ['content_subtype_value'] ) != 0) {
							
							$temp ['content_subtype_value'] = $page ['content_subtype_value'];
						
						}
						
						//if is post we will get the categories						

						if ($page ['content_type'] == 'post') {
							
							if ($options ['start_from_category'] != false) {
								
								$some_categories = $this->contentGetActiveCategoriesForPostIdAndCache ( $page ['id'], intval ( $options ['start_from_category'] ) );
							
							} else {
								
								$some_categories = $this->contentGetActiveCategoriesForPostIdAndCache ( $page ['id'] );
							
							}
							
							//$some_categories = $active_categories_for_nav;							

							//  var_dump($some_categories);							

							if (! empty ( $some_categories )) {
								
								$some_categories = array_reverse ( $some_categories );
								
								foreach ( $some_categories as $item ) {
									
									$cat_url = $this->taxonomyGetUrlForTaxonomyId ( $item );
									
									$cat_info = $this->taxonomyGetSingleItemById ( $item );
									
									$cat_temp = array ();
									
									$cat_temp ['taxonomy_id'] = $item;
									
									$cat_temp ['title'] = $cat_info ['taxonomy_value'];
									
									$cat_temp ['url'] = $cat_url;
									
									$categories_already_shown [] = $item;
									
									$titles_already_shown [] = $cat_info ['taxonomy_value'];
									
									//check for dupes									

									$skip = false;
									
									if (! empty ( $the_nav )) {
										
										foreach ( $the_nav as $nav_item ) {
											
											if ($nav_item ['content_subtype_value'] == $cat_temp ['taxonomy_id']) {
												
												$skip = true;
											
											}
										
										}
									
									}
									
									if ($skip == false) {
										
										$the_nav [] = $cat_temp;
									
									}
									
								//var_dump($cat_url);								

								}
								
							//var_dump($some_categories);							

							}
						
						}
						
						$the_nav [] = $temp;
						
						//if (intval ( $page ['content_subtype_value'] ) != 0) {						

						if ($page ['content_subtype'] == 'blog_section') {
							
							$active_categories = $this->contentActiveCategoriesForPageIdAndCache ( $page ['id'], $the_url, true );
							
							if (! empty ( $active_categories )) {
								
								//  $active_categories_children = $this->taxonomyGetChildrenItemsIdsRecursiveAndCache ( $active_categories [0] ,'category' );								

								//  var_dump ( $active_categories );								

								$active_categories_children = $this->taxonomyGetParentItemsAndReturnOnlyIds ( $active_categories [0], 'category' );
								
								if (! empty ( $active_categories_children )) {
									
									$active_categories_children = array_reverse ( $active_categories_children );
									
									$active_categories = $active_categories_children;
								
								}
								
								//var_dump($active_categories_children);								

								foreach ( $active_categories as $item ) {
									
									$cat_url = $this->taxonomyGetUrlForTaxonomyId ( $item );
									
									$cat_info = $this->taxonomyGetSingleItemById ( $item );
									
									$cat_temp = array ();
									
									$cat_temp ['taxonomy_id'] = $item;
									
									$categories_already_shown [] = $item;
									
									$titles_already_shown [] = $cat_info ['taxonomy_value'];
									
									$cat_temp ['title'] = $cat_info ['taxonomy_value'];
									
									$cat_temp ['url'] = $cat_url;
									
									//check for dupes									

									$skip = false;
									
									if (! empty ( $the_nav )) {
										
										foreach ( $the_nav as $nav_item ) {
											
											if ($nav_item ['content_subtype_value'] == $cat_temp ['taxonomy_id']) {
												
												$skip = true;
											
											}
										
										}
									
									}
									
									if ($the_url == $cat_temp ['url']) {
										
										break;
									
									}
									
									if ($skip == false) {
										
										$the_nav [] = $cat_temp;
									
									}
								
								}
							
							}
							
						/*$item = $page ['content_subtype_value'];*/
						
						}
						
					//}					

					//var_dump ( $page );					

					}
				
				}
			
			}
			
			if (! empty ( $active_categories_for_nav )) {
				
				foreach ( $active_categories_for_nav as $item ) {
					
					if (! in_array ( $item, $categories_already_shown )) {
						
						//var_dump($item)   ;						

						$categories_already_shown [] = $item;
						
						$cat_url = $this->taxonomyGetUrlForTaxonomyId ( $item );
						
						$cat_info = $this->taxonomyGetSingleItemById ( $item );
						
						$cat_temp = array ();
						
						$cat_temp ['taxonomy_id'] = $item;
						
						if (! in_array ( $cat_info ['taxonomy_value'], $titles_already_shown )) {
							
							$cat_temp ['title'] = $cat_info ['taxonomy_value'];
							
							$titles_already_shown [] = $cat_info ['taxonomy_value'];
							
							$cat_temp ['url'] = $cat_url;
							
							$the_nav [] = $cat_temp;
						
						}
					
					}
				
				}
			
			}
		
		}
		
		$this->core_model->cacheWriteAndEncode ( $the_nav, $function_cache_id, $cache_group = 'global' );
		
		return $the_nav;
		
	//var_dump ( $content_id, $the_url );	

	}
	
	function getMenuByMenuUnuqueId($uid) {
		
		$data = false;
		
		$data ['menu_unique_id'] = $uid;
		
		$data ['item_type'] = 'menu';
		
		$data = $this->getMenus ( $data, $orderby = false );
		
		$data = $data [0];
		
		return $data;
	
	}
	
	function getMenuItemsByMenuUnuqueId($uid, $set_active_to_all = true) {
		
		$check = getCurentURL ();
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $check . $function_cache_id );
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, 'menus' );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		$data = false;
		
		$data ['menu_unique_id'] = $uid;
		
		$data ['item_type'] = 'menu';
		
		$data = $this->getMenus ( $data, $orderby = false );
		
		$data = $data [0];
		
		$id = $data ['id'];
		
		$itmes = $this->getMenuItems ( $id );
		
		$the_curent_url = $this->uri->uri_string ();
		
		//is slash		

		$slash = substr ( "$the_curent_url", 0, 1 );
		
		if ($slash == '/') {
			
			$the_curent_url = substr ( "$the_curent_url", 1, strlen ( $the_curent_url ) );
		
		}
		
		//print $the_curent_url;		

		//print ACTIVE_CONTENT_ID;		

		$menu_data = array ();
		
		foreach ( $itmes as $item ) {
			
			$data = array ();
			
			$data_to_append = $item;
			
			$data_to_append ['content_id'] = $item ['content_id'];
			
			$data ['id'] = $item ['content_id'];
			
			$content_item = $this->getContentAndCache ( $data, $orderby = false, $limit = array (0, 1 ), $count_only = false, $short_data = true );
			
			$content_item = $content_item [0];
			
			$url = false;
			
			if (! empty ( $content_item )) {
				
				$data_to_append ['title'] = $content_item ['content_title'];
			
			} else {
				
				$data_to_append ['title'] = $item ['item_title'];
			
			}
			
			if (trim ( $data_to_append ['content_title'] ) == '') {
				
				$data_to_append ['content_title'] = $content_item ['content_title'];
			
			}
			
			$data_to_append ['content_id'] = $content_item ['id'];
			
			if (trim ( $data_to_append ['item_title'] ) == '') {
				
				$data_to_append ['item_title'] = $data_to_append ['title'];
			
			}
			
			$url = site_url ( $content_item ['content_url'] );
			
			if ($content_item ['is_home'] == 'y') {
				
				$url = site_url ();
			
			}
			
			if (intval ( $item ['content_id'] ) == 0) {
				
				$url = ($item ['menu_url']);
			
			}
			
			$data_to_append ['url'] = $url;
			
			$is_active = false;
			
			//print $url;			

			//print site_url ( $the_curent_url );			

			//print "<hr>";			

			if ($url == site_url ( $the_curent_url )) {
				
			//  $is_active = true;			

			}
			
			if ($content_item ['is_home'] == 'y') {
				
			//  if($the_curent_url = ''){			

			//      $is_active = true;			

			//  }			

			}
			
			if (intval ( $GLOBALS ['ACTIVE_PAGE_ID'] ) == intval ( $content_item ['id'] )) {
				
				$is_active = true;
			
			}
			
			if (intval ( ACTIVE_PAGE_ID ) == intval ( $content_item ['id'] )) {
				
				$is_active = true;
			
			}
			
			/*      var_dump(ACTIVE_PAGE_ID, $content_item ['id']);            print "<br>";*/
			
			$parents = $this->getParentPagesIdsForPageIdAndCache ( ACTIVE_PAGE_ID );
			
			//var_dump($parents);			

			if (! empty ( $parents )) {
				
				foreach ( $parents as $parent ) {
					
					if (intval ( $parent ) == intval ( $content_item ['id'] )) {
						
						$is_active = true;
					
					}
				
				}
			
			}
			
			if (intval ( $item ['content_id'] ) == 0) {
				
				$check = getCurentURL ();
				
				if ($check == $item ['the_url']) {
					
					$is_active = true;
				
				}
			
			}
			
			//check we are on subpage and if the parent is active is active?			

			$data_to_append ['is_active'] = $is_active;
			
			$menu_data [] = $data_to_append;
		
		}
		
		if ($set_active_to_all == false) {
			
			$i = 0;
			
			$menu_data2 = array ();
			
			$menu_data_to_unset = array ();
			
			foreach ( $menu_data as $item ) {
				
				if ($item ['is_active'] == true) {
					
					$menu_data_to_unset [] = $i;
				
				}
				
				$i ++;
			
			}
			
			if (! empty ( $menu_data_to_unset )) {
				
				array_pop ( $menu_data_to_unset );
				
				if (! empty ( $menu_data_to_unset )) {
					
					foreach ( $menu_data_to_unset as $item ) {
						
						$menu_data [$item] ['is_active'] = false;
					
					}
				
				}
			
			}
		
		}
		
		$this->core_model->cacheWriteAndEncode ( $menu_data, $function_cache_id, $cache_group = 'menus' );
		
		return $menu_data;
	
	}
	
	function getMenuItems($menu_id) {
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, 'menus' );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		global $cms_db_tables;
		
		$table_menus = $cms_db_tables ['table_menus'];
		
		$table_content = $cms_db_tables ['table_content'];
		
		$data = false;
		
		$data ['item_parent'] = $menu_id;
		
		$data ['item_type'] = 'menu_item';
		
		$orderby = array ();
		
		$orderby [0] = 'position';
		
		$orderby [1] = 'asc';
		
		$data = $this->getMenus ( $data, $orderby );
		
		$return = array ();
		
		foreach ( $data as $item ) {
			
			if (intval ( $item ['content_id'] ) == 0) {
			
			}
			
			//$menu_item = $item;			

			if ($item ['content_id'] != 0) {
				
				$check_if_this_is_page_or_post = array ();
				
				$check_if_this_is_page_or_post ['id'] = $item ['content_id'];
				
				$check_if_this_is_page_or_post ['only_those_fields'] = array ('content_type', 'id' );
				
				//          //				

				$check_if_this_is_page_or_post = $this->getContentAndCache ( $check_if_this_is_page_or_post, $orderby = false, $limit = false, $count_only = false, $short_data = true );
				
				$check_if_this_is_page_or_post = $check_if_this_is_page_or_post [0] ['content_type'];
				
				if ($check_if_this_is_page_or_post == 'post') {
					
					$url = $this->contentGetHrefForPostId ( $item ['content_id'] );
				
				}
				
				if ($check_if_this_is_page_or_post == 'page') {
					
					$url = $this->getContentURLByIdAndCache ( $item ['content_id'] );
				
				}
				
				if (intval ( $item ['content_id'] ) != 0) {
					
					if (strval ( $item ['item_title'] ) == '') {
						
						$q = " select content_title from $table_content where id={$item ['content_id']}  limit 0,1 ";
						
						$q = $this->core_model->dbQuery ( $q, md5 ( $q ), 'content/' . $item ['content_id'] );
						
						$fix_title = $q [0] ['content_title'];
						
						//  print $fix_title;						

						$item ['item_title'] = $fix_title;
						
						$q = "update $table_menus set item_title='$fix_title' where id={$item['id']}";
						
						$this->core_model->dbQ ( $q );
						
					//  var_Dump ( $q );					

					//  exit ();					

					}
				
				}
			
			} 

			elseif ($item ['taxonomy_id'] != 0) {
				
				if (strval ( $item ['item_title'] ) == '') {
					
					$get_taxonomy = $this->taxonomyGetSingleItemById ( $item ['taxonomy_id'] );
					
					$fix_title = $get_taxonomy ['taxonomy_value'];
					
					$item ['item_title'] = $fix_title;
					
					$q = "update $table_menus set item_title='$fix_title' where id={$item['id']}";
					
					$this->core_model->dbQ ( $q );
					
				//  var_Dump ( $q );				

				//  exit ();				

				}
				
				//				

				$url = $this->taxonomyGetUrlForTaxonomyId ( $item ['taxonomy_id'] );
			
			} else {
				
				$url = trim ( $item ['menu_url'] );
			
			}
			
			$item ['the_url'] = $url;
			
			$return [] = $item;
		
		}
		
		$this->core_model->cacheWriteAndEncode ( $return, $function_cache_id, 'menus' );
		
		return $return;
	
	}
	
	function menusGetThumbnailImageById($id, $size = 128, $direction = "DESC") {
		
		//$data ['id'] = $id;		

		//$data = $this->taxonomyGet ( $data );		

		//var_dump ( $data );		

		$data = $this->core_model->mediaGetThumbnailForItem ( $to_table = 'table_menus', $to_table_id = $id, $size, $direction );
		
		return $data;
	
	}
	
	function getMenuItemsByMenuName($name) {
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		$data = false;
		
		$data ['item_title'] = $name;
		
		$data ['item_type'] = 'menu';
		
		$orderby = array ();
		
		$orderby [0] = 'position';
		
		$orderby [1] = 'asc';
		
		$data = $this->getMenus ( $data, $orderby );
		
		$the_menu = $data [0];
		
		$the_menu = $this->getMenuItems ( $the_menu ['id'] );
		
		//var_dump($the_menu);		

		$the_curent_url = $this->uri->uri_string ();
		
		//is slash		

		$slash = substr ( "$the_curent_url", 0, 1 );
		
		if ($slash == '/') {
			
			$the_curent_url = substr ( "$the_curent_url", 1, strlen ( $the_curent_url ) );
		
		}
		
		//print $the_curent_url;		

		//print ACTIVE_CONTENT_ID;		

		$menu_data = array ();
		
		foreach ( $the_menu as $item ) {
			
			$data = array ();
			
			$data_to_append = array ();
			
			$data ['id'] = $item ['content_id'];
			
			//			

			$content_item = $this->getContent ( $data, $orderby = false, $limit = false, $count_only = false, $short_data = true );
			
			$content_item = $content_item [0];
			
			$url = false;
			
			if (! empty ( $content_item )) {
				
				$data_to_append ['title'] = $content_item ['content_title'];
			
			} else {
				
				$data_to_append ['title'] = $item ['item_title'];
			
			}
			
			$url = site_url ( $content_item ['content_url'] );
			
			if ($content_item ['is_home'] == 'y') {
				
				$url = site_url ();
			
			}
			
			if (intval ( $item ['content_id'] ) == 0) {
				
				$url = ($item ['menu_url']);
			
			}
			
			$data_to_append ['url'] = $url;
			
			$is_active = false;
			
			//print $url;			

			//print site_url ( $the_curent_url );			

			//print "<hr>";			

			if ($url == site_url ( $the_curent_url )) {
				
			//  $is_active = true;			

			}
			
			if ($content_item ['is_home'] == 'y') {
				
			//  if($the_curent_url = ''){			

			//      $is_active = true;			

			//  }			

			}
			
			if (intval ( $GLOBALS ['ACTIVE_PAGE_ID'] ) == intval ( $content_item ['id'] )) {
				
				$is_active = true;
			
			}
			
			if (intval ( $item ['content_id'] ) == 0) {
				
				$check = getCurentURL ();
				
				if ($check == $item ['menu_url']) {
					
					$is_active = true;
				
				}
			
			}
			
			$data_to_append ['is_active'] = $is_active;
			
			$menu_data [] = $data_to_append;
		
		}
		
		//$		

		//var_dump ( $menu_data );		

		$this->core_model->cacheWriteAndEncode ( $menu_data, $function_cache_id );
		
		return $menu_data;
	
	}
	
	function reorderMenuItem($direction, $id) {
		
		$this->fixMenusPositions ();
		
		if (intval ( $id ) == 0) {
			
			//$this->fixMenusPositions ();			

			return false;
		
		}
		
		$data = false;
		
		$this->core_model->cleanCacheGroup ( 'menus' );
		
		$data ['id'] = $id;
		
		$data = $this->getMenus ( $data );
		
		$data = $data [0];
		
		if (empty ( $data )) {
			
			$this->fixMenusPositions ();
			
			return false;
		
		} else {
			
			$curent_item = $data;
		
		}
		
		//get next		

		$data = false;
		
		$this->core_model->cleanCacheGroup ( 'menus' );
		
		$data ['item_parent'] = $curent_item ['item_parent'];
		
		$data ['item_type'] = $curent_item ['item_type'];
		
		$data ['position'] = intval ( $curent_item ['position'] ) + 1;
		
		$data = $this->getMenus ( $data );
		
		$next_item = $data [0];
		
		//get prev		

		$this->core_model->cleanCacheGroup ( 'menus' );
		
		$data = false;
		
		$data ['item_parent'] = $curent_item ['item_parent'];
		
		$data ['item_type'] = $curent_item ['item_type'];
		
		$data ['position'] = intval ( $curent_item ['position'] ) - 1;
		
		$data = $this->getMenus ( $data );
		
		$prev_item = $data [0];
		
		if ($direction == 'up') {
			
			if (empty ( $prev_item )) {
				
				$this->fixMenusPositions ();
				
				return false;
			
			} else {
				
				//update curent				

				$table = TABLE_PREFIX . 'menus';
				
				$q = "UPDATE $table set position={$prev_item['position']} where id={$curent_item ['id']}   ";
				
				$sql_q = $this->core_model->dbQ ( $q );
				
				$q = "UPDATE $table set position={$curent_item['position']} where id={$prev_item['id']}   ";
				
				$sql_q = $this->core_model->dbQ ( $q );
				
				$this->core_model->cleanCacheGroup ( 'menus' );
				
				$this->fixMenusPositions ();
				
				return true;
			
			}
		
		}
		
		if ($direction == 'down') {
			
			if (empty ( $next_item )) {
				
				$this->fixMenusPositions ();
				
				return false;
			
			} else {
				
				$table = TABLE_PREFIX . 'menus';
				
				$q = "UPDATE $table set position={$next_item['position']} where id={$curent_item ['id']}   ";
				
				$sql_q = $this->core_model->dbQ ( $q );
				
				$q = "UPDATE $table set position={$curent_item['position']} where id={$next_item['id']}   ";
				
				$sql_q = $this->core_model->dbQ ( $q );
				
				$this->core_model->cleanCacheGroup ( 'menus' );
				
				$this->fixMenusPositions ();
				
				return true;
			
			}
		
		}
	
	}
	
	/**	 * @desc fix Menus Positions	 * @author Peter Ivanov	 *	 */
	
	function fixMenusPositions() {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_menus'];
		
		$table_content = $cms_db_tables ['table_content'];
		
		//$table = TABLE_PREFIX . 'menus';		

		//get all menus		

		$sql = "SELECT * from $table where item_type='menu'  ";
		
		$q = $this->core_model->sqlQuery ( $sql );
		
		$results = $q;
		
		foreach ( $results as $item ) {
			
			$sql = "SELECT * from $table where item_type='menu_item'  and item_parent='{$item['id']}' order by position ASC ";
			
			$q = $this->core_model->sqlQuery ( $sql );
			
			$i = 1;
			
			//var_dump($q);			

			foreach ( $q as $menu_item ) {
				
				if (intval ( $menu_item ['content_id'] ) != 0) {
					
					$check = "SELECT id from $table_content where id={$menu_item['content_id']} ";
					
					$check = $this->core_model->dbQuery ( $check );
					
					$check = $check [0];
					
					if (intval ( $check ['id'] ) != 0) {
						
						$check = true;
					
					} else {
						
						$check = false;
					
					}
				
				} else {
					
					$check = true;
				
				}
				
				if ($check == true) {
					
					$query = " update $table set position=$i where id={$menu_item['id']} ";
					
					$query = $this->db->query ( $query );
					
					$i ++;
				
				} else {
					
					//delete					

					$check = "delete from $table where id={$menu_item['id']} ";
					
					$check = $this->core_model->dbQ ( $check );
				
				}
			
			}
		
		}
		
		$this->core_model->cleanCacheGroup ( 'menus' );
		
		return true;
	
	}
	
	function removeContentFromUnusedMenus($content_id, $menus_array) {
		
		if (empty ( $menus_array )) {
			
			return false;
		
		}
		
		$table = TABLE_PREFIX . 'menus';
		
		$menus_array = implode ( ',', $menus_array );
		
		$q = "DELETE FROM $table where content_id=$content_id and item_parent NOT IN ($menus_array)  ";
		
		$sql_q = $this->db->query ( $q );
		
		$this->core_model->cleanCacheGroup ( 'menus' );
		
		return true;
	
	}
	
	function metaTagsGenerateByContentId($id, $posts_data = false, $selected_taxonomy = false) {
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		$content_id = intval ( $id );
		$cache_group = 'content/' . $content_id;
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		if (! empty ( $selected_taxonomy )) {
			
			$selected_taxonomy_titles = array ();
			
			$selected_taxonomy_descs = array ();
			
			//$data = $this->taxonomyGenerateTagsFromString ( $data, 3 );			

			//$meta ['content_meta_keywords'] = $data;			

			foreach ( $selected_taxonomy as $the_taxonomy ) {
				
				$the_taxonomy_full = $this->taxonomyGetSingleItemById ( $the_taxonomy );
				
				$selected_taxonomy_titles [] = $the_taxonomy_full ['taxonomy_value'];
				
				$selected_taxonomy_descs [] = $the_taxonomy_full ['taxonomy_description'];
			
			}
			
			$meta = array ();
			
			$meta ["content_meta_title"] = implode ( ', ', $selected_taxonomy_titles );
			
			$meta ["content_meta_description"] = implode ( ', ', $selected_taxonomy_descs );
			
			//			$keyrods = $this->taxonomyGenerateTagsFromString ( implode ( ', ', $selected_taxonomy_descs ), 3 );
			

			$meta ["content_meta_keywords"] = $keyrods;
			
			$this->core_model->cacheWriteAndEncode ( $meta, $function_cache_id, $cache_group );
			
			return $meta;
		
		}
		
		$data ['id'] = $id;
		
		//($data, $orderby = false, $limit = false, $count_only = false, $short_data = false, $only_fields = false)		

		$only_fields = array ();
		
		$only_fields [] = 'content_meta_title';
		
		$only_fields [] = 'content_meta_description';
		
		$only_fields [] = 'content_meta_keywords';
		
		$only_fields [] = 'content_meta_other_code';
		
		$only_fields [] = 'content_subtype';
		
		$only_fields [] = 'content_subtype_value';
		
		$only_fields [] = 'content_subtype';
		
		$only_fields [] = 'content_title';
		
		$only_fields [] = 'content_body';
		
		$data = $this->getContentAndCache ( $data, $orderby = false, $limit = array (0, 1 ), $count_only = false, $short_data = true, $only_fields = $only_fields );
		
		$data = $data [0];
		
		$content = $data;
		
		$meta = array ();
		
		if (empty ( $data )) {
			
			$meta = array ();
			
			$meta ["content_meta_title"] = $content ['content_title'];
			
			$meta ["content_meta_title"] = ($content ['content_meta_title'] != '') ? $content ['content_meta_title'] : $this->content_model->optionsGetByKey ( 'content_meta_title' );
			
			$meta ["content_meta_description"] = ($content ['content_meta_description'] != '') ? $content ['content_meta_description'] : $this->content_model->optionsGetByKey ( 'content_meta_description' );
			
			$meta ["content_meta_keywords"] = ($content ['content_meta_keywords'] != '') ? $content ['content_meta_keywords'] : $this->content_model->optionsGetByKey ( 'content_meta_keywords' );
			
			$meta ["content_meta_other_code"] = ($content ['content_meta_other_code'] != '') ? $content ['content_meta_other_code'] : $this->content_model->optionsGetByKey ( 'content_meta_other_code' );
			
			$this->core_model->cacheWriteAndEncode ( $meta, $function_cache_id, $cache_group );
			
			return $meta;
		
		} else {
			
			$meta = array ();
			
			if ($content ['content_meta_title'] != '') {
				
				$meta ['content_meta_title'] = $content ['content_meta_title'];
			
			} else {
				
				$data = trim ( $content ['content_title'] );
				
				$data = htmlspecialchars_decode ( $data );
				
				$data = strip_tags ( $data );
				
				$data = str_ireplace ( '&nbsp;', ' ', $data );
				
				$data = reduce_multiples ( $data );
				
				$data = strip_quotes ( $data );
				
				$data = addslashes ( $data );
				
				$data = mb_trim ( $data );
				
				$data = trim ( $data );
				
				$meta ['content_meta_title'] = $data;
			
			}
			
			if ($content ['content_meta_description'] != '') {
				
				$meta ['content_meta_description'] = $content ['content_meta_description'];
			
			} else {
				
				$data = trim ( $content ['content_body'] );
				
				$data = htmlspecialchars_decode ( $data );
				
				$data = strip_tags ( $data );
				
				$data = str_ireplace ( '&nbsp;', ' ', $data );
				
				$data = reduce_multiples ( $data );
				
				$data = strip_quotes ( $data );
				
				$data = addslashes ( $data );
				
				$data = mb_trim ( $data );
				
				$data = trim ( $data );
				
				$data = word_limiter ( $data, 20, '...' );
				
				$meta ['content_meta_description'] = $data;
			
			}
			
			if ($content ['content_meta_keywords'] != '') {
				
				$meta ['content_meta_keywords'] = $content ['content_meta_keywords'];
			
			} else {
				
				$data = trim ( $content ['content_body_nohtml'] );
				
				//var_dump($data);				

				//				$data = $this->taxonomyGenerateTagsFromString ( $data, 3 );
				

				$meta ['content_meta_keywords'] = $data;
				
				//  var_dump($data);				

				//exit;				

				if ($meta ['content_meta_keywords'] == '') {
					
					if ($content ['content_subtype'] == 'blog_section') {
						
						$temp = $this->taxonomyGetChildrenItemsIdsRecursiveAndCache ( $content ['content_subtype_value'], 'category' );
						
						if (! empty ( $temp )) {
							
							$taxonomy_tree = array ();
							
							foreach ( $temp as $tax_id ) {
								
								$temp_data = $this->taxonomyGetSingleItemById ( $tax_id );
								
								$taxonomy_tree [] = trim ( $temp_data ['taxonomy_value'] );
							
							}
							
							$taxonomy_tree = array_unique ( $taxonomy_tree );
							
							$taxonomy_tree = implode ( ', ', $taxonomy_tree );
							
							$meta ['content_meta_keywords'] = $taxonomy_tree;
							
							if ($meta ['content_meta_description'] == '') {
								
								$meta ['content_meta_description'] = $meta ['content_meta_keywords'];
							
							}
							
							if (! empty ( $posts_data )) {
								
								$temp_data = $this->taxonomyGetSingleItemById ( $selected_taxonomy [0] );
								
								$meta ['content_meta_title'] = $temp_data ['taxonomy_value'];
								
								//var_dump($posts_data);								

								$categories = array ();
								
								foreach ( $posts_data as $thepost ) {
									
									$keyword_calculate = $keyword_calculate . ' ' . $thepost ['content_title'] . ' ' . $thepost ['content_body'];
								
								}
								
								$keyword_calculate = $this->taxonomyGenerateTagsFromString ( $keyword_calculate );
								
								$keyword_calculate = word_limiter ( $keyword_calculate, 40, ' ' );
								
								$meta ['content_meta_keywords'] = $keyword_calculate;
								
								if ($meta ['content_meta_description'] == '') {
									
									$meta ['content_meta_description'] = $meta ['content_meta_keywords'];
								
								}
							
							}
						
						}
					
					}
				
				}
			
			}
			
			$this->core_model->cacheWriteAndEncode ( $meta, $function_cache_id, $cache_group );
			
			return $meta;
		
		}
	
	}
	
	function taxonomy_helpers_generateTagCloud($href = false, $criteria = false, $beginning_only_with_letter = false, $clould_order = false, $clould_limits = false, $min_max_sizes = false, $only_for_categories = false) {
		
		global $cms_db_tables;
		
		//return false;		

		$table = $cms_db_tables ['table_taxonomy'];
		
		//$sql = "SELECT taxonomy_value from $table where taxonomy_type='tag' and taxonomy_value LIKE ('%$item%') group by taxonomy_value  limit 1 ";		

		//      $q = $this->core_model->sqlQuery ( $sql );		

		//var_dump($criteria);		

		if (! empty ( $criteria )) {
			
			$get = $this->core_model->getDbData ( $table, $criteria, $limit = false, $offset = false, $orderby = false, $cache_group = 'taxonomy/global' );
			
			if (! empty ( $get )) {
				
				$ids = array ();
				
				foreach ( $get as $item ) {
					
					$ids [] = intval ( $item ['id'] );
				
				}
			
			}
			
			$ids = array_unique ( $ids );
		
		}
		
		if (! empty ( $ids )) {
			
			$idq = " AND id IS NOT NULL and (id=0  ";
			
			foreach ( $ids as $i ) {
				
				$idq .= " OR id=$i  ";
			
			}
			
			$idq .= " )   ";
		
		}
		
		if (is_array ( $only_for_categories ) == true) {
			
			$chidlern_ids = array ();
			
			foreach ( $only_for_categories as $only_for_cat )
				
				$cchidlern_ids = $this->taxonomyGetChildrenItems ( $only_for_cat );
			
			if (! empty ( $cchidlern_ids )) {
				
				foreach ( $cchidlern_ids as $temp ) {
					
					$chidlern_ids [] = $temp ['id'];
				
				}
			
			}
			
			if (! empty ( $chidlern_ids )) {
				
				//var_dump($chidlern_ids);				

				$chidlern_ids_implode = implode ( ',', $chidlern_ids );
				
				$q = " SELECT id, to_table_id from $table where taxonomy_type='category_item' and id IN ($chidlern_ids_implode) group  by   to_table_id ";
				
				$q = $this->core_model->dbQuery ( $q );
				
				//var_dump($q);				

				$some_more_ids = array ();
				
				foreach ( $q as $some ) {
					
					$some_more_ids [] = $some ['to_table_id'];
				
				}
				
				if (! empty ( $some_more_ids )) {
					
					$some_more_idsimplode = implode ( ',', $some_more_ids );
					
					$idq .= " AND to_table_id IN ($some_more_idsimplode)  ";
				
				}
			
			}
			
		//$qq1 =  "  SELECT id, to_table_id, to_table from  $table where   "		

		}
		
		if ($beginning_only_with_letter != '') {
			
			$beginning_only_with_letter_q = " AND LEFT(taxonomy_value,1) = '$beginning_only_with_letter' ";
		
		} else {
			
			$beginning_only_with_letter_q = false;
		
		}
		
		//AND LEFT(taxonomy_value,1) = 'v'		

		if ($clould_order != false) {
			
			$order_q = " ORDER BY {$clould_order[0]} {$clould_order[1]}  ";
		
		} else {
			
			$order_q = " ORDER BY taxonomy_value ASC ";
		
		}
		
		if ($clould_limits != false) {
			
			$limit_q = " LIMIT {$clould_limits[0]}, {$clould_limits[1]}  ";
		
		} else {
			
			$limit_q = "  ";
		
		}
		
		$query = "SELECT *, COUNT(id) AS quantity        ,  LEFT(taxonomy_value,1)  as taxonomy_value_letter        FROM $table        where taxonomy_type='tag' $idq        $beginning_only_with_letter_q        GROUP BY taxonomy_value        $order_q        $limit_q        ";
		
		$q = $this->core_model->sqlQuery ( $query );
		
		foreach ( $q as $row ) {
			
			//$tags [$row ['tag']] = $row ['quantity'];			

			//$tags [$row ['taxonomy_value']] = $row ['quantity'];			

			$tags [] = $row;
			
			$tags_velues [$row ['taxonomy_value']] = $row ['quantity'];
		
		}
		
		// change these font sizes if you will		

		if (empty ( $min_max_sizes )) {
			
			$max_size = 250; // max font size in %			

			$min_size = 100; // min font size in %		

		} else {
			
			$max_size = $min_max_sizes [1]; // max font size in %			

			$min_size = $min_max_sizes [0]; // min font size in %		

		}
		
		// get the largest and smallest array values		

		if (! empty ( $tags_velues )) {
			
			$max_qty = max ( array_values ( $tags_velues ) );
			
			$min_qty = min ( array_values ( $tags_velues ) );
		
		} else {
			
			$max_qty = false;
			
			$min_qty = false;
		
		}
		
		// find the range of values		

		$spread = $max_qty - $min_qty;
		
		if (0 == $spread) { // we don't want to divide by zero			

			$spread = 1;
		
		}
		
		// determine the font-size increment		

		// this is the increase per tag quantity (times used)		

		$step = ($max_size - $min_size) / ($spread);
		
		// loop through our tag array		

		//foreach ( $tags as $key => $value ) {		

		if (! empty ( $tags )) {
			
			foreach ( $tags as $item ) {
				
				$the_href = false;
				
				$the_href = $href;
				
				// calculate CSS font-size				

				// find the $value in excess of $min_qty				

				// multiply by the font-size increment ($size)				

				// and add the $min_size set above				

				$size = $min_size + (($item ['quantity'] - $min_qty) * $step);
				
				// uncomment if you want sizes in whole %:				

				// $size = ceil($size);				

				if ($href == false) {
					
				//  $href = "#";				

				}
				
				foreach ( $item as $k => $v ) {
					
					$the_href = str_ireplace ( "{" . $k . "}", $v, $the_href );
				
				}
				
				//				

				if (empty ( $ids )) {
					
					$to_print = true;
				
				} else {
					
					if (in_array ( $item ['id'], $ids ) == true) {
						
						$to_print = true;
					
					} else {
						
						$to_print = false;
					
					}
				
				}
				
				if ($to_print == true) {
					
					// you'll need to put the link destination in place of the #					

					// (assuming your tag links to some sort of details page)					

					echo '<a href="' . $the_href . '" style="font-size: ' . $size . '%"';
					
					// perhaps adjust this title attribute for the things that are tagged					

					echo ' title="' . $item ['quantity'] . ' things tagged with ' . $item ['taxonomy_value'] . '"';
					
					echo '>' . $item ['taxonomy_value'] . '</a> ';
				
				}
				
			// notice the space at the end of the link			

			}
		
		}
	
	}
	
	function taxonomySave($data, $preserve_cache = false) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_taxonomy'];
		
		if ($data ['content_body'] == '') {
			
			$data ['content_body'] = $this->parseContentBodyItems ( $data ['content_body'], $data ['taxonomy_value'] );
		
		}
		
		if ($data ['taxonomy_silo_keywords']) {
			
			$data ['taxonomy_silo_keywords'] = strip_tags ( $data ['taxonomy_silo_keywords'] );
		
		}
		
		$content_ids = false;
		
		if ($data ['content_id']) {
			
			if (is_array ( $data ['content_id'] ) and ! empty ( $data ['content_id'] ) and trim ( $data ['taxonomy_type'] ) != '') {
				
				//p($data, 1);				

				$content_ids = $data ['content_id'];
			
			}
		
		}
		
		$save = $this->core_model->saveData ( $table, $data );
		
		if (intval ( $save ) == 0) {
			
			return false;
		
		}
		
		if (! empty ( $content_ids )) {
			
			$content_ids = array_unique ( $content_ids );
			
			//  p($content_ids, 1);			

			$taxonomy_type = trim ( $data ['taxonomy_type'] ) . '_item';
			
			$content_ids_all = implode ( ',', $content_ids );
			
			$q = "delete from $table where to_table='table_content'            and content_type='post'            and parent_id=$save            and  taxonomy_type ='{$taxonomy_type}' ";
			
			//p($q,1);			

			$this->core_model->dbQ ( $q );
			
			foreach ( $content_ids as $id ) {
				
				$item_save = array ();
				
				$item_save ['to_table'] = 'table_content';
				
				$item_save ['to_table_id'] = $id;
				
				$item_save ['taxonomy_type'] = $taxonomy_type;
				
				$item_save ['content_type'] = 'post';
				
				$item_save ['parent_id'] = intval ( $save );
				
				$item_save = $this->core_model->saveData ( $table, $item_save );
				
				$this->core_model->cleanCacheGroup ( 'content' . DIRECTORY_SEPARATOR . $id );
			
			}
		
		}
		
		$this->taxonomyFixPositionsForId ( $save );
		
		//  $this->core_model->cleanCacheGroup ( 'taxonomy' );		

		if ($preserve_cache == false) {
			
			//$this->core_model->cleanCacheGroup ( 'taxonomy' );
			$this->core_model->cleanCacheGroup ( 'taxonomy' . DIRECTORY_SEPARATOR . $save );
			$this->core_model->cleanCacheGroup ( 'taxonomy' . DIRECTORY_SEPARATOR . '0' );
			$this->core_model->cleanCacheGroup ( 'taxonomy' . DIRECTORY_SEPARATOR . 'global' );
		}
		
		return $save;
	
	}
	
	function taxonomyGetIds($data = false, $orderby = false) {
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_taxonomy'];
		
		if ($orderby == false) {
			
			$orderby [0] = 'updated_on';
			
			$orderby [1] = 'DESC';
		
		}
		
		if (($data ['taxononomy_type']) == '') {
			
			$limit = false;
		
		} else {
			
			$limit = false;
		
		}
		
		if ($no_limits == true) {
			
			$limit = false;
		
		}
		
		//  print ($data['taxononomy_type']);		

		if (($data ['taxonomy_type']) == 'category') {
			
			$limit = false;
		
		}
		
		$save = $this->core_model->getDbData ( $table, $data, $limit, $offset = false, $orderby, $cache_group = 'taxonomy/global', $debug = false, $ids = false, $count_only = false, $only_those_fields = array ('id' ), $exclude_ids = false, $force_cache_id = $function_cache_id );
		
		if (empty ( $save )) {
			
			return false;
		
		}
		
		$ids = array ();
		
		foreach ( $save as $item ) {
			
			$ids [] = $item ['id'];
		
		}
		
		return $ids;
	
	}
	
	function taxonomyGet($data = false, $orderby = false, $no_limits = false, $no_cache = false) {
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id .= $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		if (intval ( $data ['id'] ) != 0) {
			$data = $this->taxonomyGetSingleItemById ( $data ['id'] );
			return $data;
		} elseif (intval ( $data ['parent_id'] ) != 0) {
			$data = $this->taxonomyGetSingleItemById ( $data ['parent_id'] );
			return $data;
		
		} else {
			
			$function_cache_id = __FUNCTION__ . '_' . $data ['taxononomy_type'] . '_' . $data ['to_table'] . '_' . $data ['to_table_id'] . '_' . md5 ( $function_cache_id );
			
			global $cms_db_tables;
			
			$table = $cms_db_tables ['table_taxonomy'];
			
			if ($orderby == false) {
				
				$orderby [0] = 'updated_on';
				
				$orderby [1] = 'DESC';
			
			}
			
			if (($data ['taxononomy_type']) == '') {
				
				$limit [0] = 0;
				
				$limit [1] = 10;
			
			} else {
				
				$limit = false;
			
			}
			
			if ($no_limits == true) {
				
				$limit = false;
			
			}
			
			//  print ($data['taxononomy_type']);			

			if (($data ['taxonomy_type']) == 'category') {
				
				$limit = false;
			
			}
			
			if ($no_cache == false) {
				
				$cache_group = 'taxonomy/global';
			
			} else {
				
				$cache_group = false;
			
			}
			
			$save = $this->core_model->getDbData ( $table, $data, $limit = $limit, $offset = false, $orderby, $cache_group = $cache_group, $debug = false, $ids = false, $count_only = false, $only_those_fields = false, $exclude_ids = false, $force_cache_id = false );
			
			return $save;
		}
	
	}
	
	function taxonomyGetThumbnailImageById($id, $size = 128) {
		
		//$data ['id'] = $id;		

		//$data = $this->taxonomyGet ( $data );		

		//var_dump ( $data );		

		$data = $this->core_model->mediaGetThumbnailForItem ( $to_table = 'table_taxonomy', $to_table_id = $id, $size );
		
		return $data;
	
	}
	
	function taxonomyGetUrlForTaxonomyIdAndCache($id) {
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$taxonomy_id = intval ( $id );
		$cache_group = 'taxonomy/' . $taxonomy_id;
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		} else {
			
			$to_cache = $this->taxonomyGetUrlForTaxonomyId ( $id );
			
			//var_dump($to_cache);			

			$this->core_model->cacheWriteAndEncode ( $to_cache, $function_cache_id, $cache_group );
			
			return $to_cache;
		
		}
	
	}
	
	function taxonomyGetUrlForTaxonomyId($id) {
		
		//return false ;		

		if (intval ( $id ) == 0) {
			
			return false;
		
		}
		
		$data = array ();
		
		$data ['id'] = $id;
		
		$data = $this->taxonomyGetSingleItemById ( $id );
		
		if (empty ( $data )) {
			
			return false;
		
		}
		
		$content = array ();
		
		$content ['content_subtype'] = 'blog_section';
		
		$content ['content_subtype_value'] = $id;
		
		$orderby = array ('id', 'desc' );
		
		$content = $this->getContentAndCache ( $content, $orderby );
		
		$content = $content [0];
		
		$url = false;
		
		if (! empty ( $content )) {
			
			if ($content ['content_type'] == 'page') {
				
				$url = $this->getContentURLByIdAndCache ( $content ['id'] );
			
			}
			
			if ($content ['content_type'] == 'post') {
				
				$url = $this->contentGetHrefForPostId ( $content ['id'] );
			
			}
		
		}
		
		if ($url != false) {
			
			return $url;
		
		}
		
		$parent_ids = $this->taxonomyGetParentIdsForId ( $data ['id'] );
		
		foreach ( $parent_ids as $item ) {
			
			$content = array ();
			
			$content ['content_subtype'] = 'blog_section';
			
			$content ['content_subtype_value'] = $item;
			
			$orderby = array ('id', 'desc' );
			
			$content = $this->getContentAndCache ( $content, $orderby );
			
			$content = $content [0];
			
			$url = false;
			
			if (! empty ( $content )) {
				
				if ($content ['content_type'] == 'page') {
					
					$url = $this->getContentURLByIdAndCache ( $content ['id'] );
					
					$url = $url . '/category:' . $data ['taxonomy_value'];
				
				}
				
				if ($content ['content_type'] == 'post') {
					
					$url = $this->contentGetHrefForPostId ( $content ['id'] );
				
				}
			
			}
			
			if ($url != false) {
				
				return $url;
			
			}
		
		}
		
		return false;
		
	//var_dump ( $parent_ids );	

	}
	
	function taxonomyCategoriesGetNextOrPrevCategoryFotCategoryId($cat_id, $next_or_prev = 'next') {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_taxonomy'];
		
		$item = $this->taxonomyGetSingleItemById ( $cat_id );
		
		$item_main = $item;
		
		if (empty ( $item )) {
			
			return false;
		
		}
		
		if ($next_or_prev == 'next') {
			
			$pos = $item ['position'];
			
			$q = " SELECT id from $table where position > $pos and taxonomy_type = 'category' and parent_id = '{$item['parent_id']}' order by position ASC  limit 0,1";
			
			$q = $this->core_model->dbQuery ( $q );
			
			$q = $q [0] ['id'];
			
			$item = $this->taxonomyGetSingleItemById ( $q );
			
			if (empty ( $item )) {
				
				$q = " SELECT id from $table where position > 0 and taxonomy_type = 'category' and parent_id = '{$item_main['id']}' order by position ASC limit 0,1";
				
				$q = $this->core_model->dbQuery ( $q );
				
				$q = $q [0] ['id'];
				
				$item = $this->taxonomyGetSingleItemById ( $q );
			
			}
			
			if (empty ( $item )) {
				
				$q = " SELECT id from $table where position > 0 and taxonomy_type = 'category' and parent_id = '{$item_main['parent_id']}' order by position ASC limit 0,1";
				
				$q = $this->core_model->dbQuery ( $q );
				
				$q = $q [0] ['id'];
				
				$item = $this->taxonomyGetSingleItemById ( $q );
			
			}
			
			return $item;
		
		}
		
		if ($next_or_prev == 'prev') {
			
			$pos = $item ['position'];
			
			$q = " SELECT id from $table where position < $pos and taxonomy_type = 'category' and parent_id = '{$item['parent_id']}' order by position DESC  limit 0,1";
			
			//var_dump($q);			

			$q = $this->core_model->dbQuery ( $q );
			
			$q = $q [0] ['id'];
			
			$item = $this->taxonomyGetSingleItemById ( $q );
			
			if (empty ( $item )) {
				
				$q = " SELECT id from $table where position < 10000 and taxonomy_type = 'category' and parent_id = '{$item_main['id']}' order by position DESC limit 0,1";
				
				//var_dump($q);				

				$q = $this->core_model->dbQuery ( $q );
				
				$q = $q [0] ['id'];
				
				$item = $this->taxonomyGetSingleItemById ( $q );
			
			}
			
			if (empty ( $item )) {
				
				$q = " SELECT id from $table where position < 10000 and taxonomy_type = 'category' and parent_id = '{$item_main['parent_id']}' order by position DESC limit 0,1";
				
				$q = $this->core_model->dbQuery ( $q );
				
				$q = $q [0] ['id'];
				
				$item = $this->taxonomyGetSingleItemById ( $q );
			
			}
			
			return $item;
		
		}
	
	}
	
	function taxonomyChangePosition($id, $direction) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_taxonomy'];
		
		if (($direction == 'up') or ($direction == 'down')) {
			
			$item = $this->taxonomyGetSingleItemById ( $id );
			
			if (empty ( $item )) {
				
				return false;
			
			} else {
				
				$cur_item = $item;
				
				$cur_position = intval ( $item ['position'] );
				
				if ($cur_position > 1) {
					
					$prev_position = $cur_position - 1;
				
				}
				
				$next_position = $cur_position + 1;
				
				if (($direction == 'up')) {
					
					$prev_item = array ();
					
					$prev_item ['parent_id'] = $cur_item ['parent_id'];
					
					$prev_item ['taxonomy_type'] = $cur_item ['taxonomy_type'];
					
					$prev_item ['position'] = $prev_position;
					
					$prev_item = $this->taxonomyGet ( $prev_item );
					
					$prev_item = $prev_item [0];
					
					if ($prev_item ['id'] != $cur_item ['id']) {
						
						$update_pos = array ();
						
						$update_pos ['id'] = $cur_item ['id'];
						
						$update_pos ['position'] = $prev_item ['position'];
						
						//$this->taxonomySave ( $update_pos );						

						$q = " UPDATE $table set position='{$update_pos['position']}' where id='{$update_pos['id']}'  ";
						
						$q = $this->core_model->dbQ ( $q );
						
						$update_pos = array ();
						
						$update_pos ['id'] = $prev_item ['id'];
						
						$update_pos ['position'] = $cur_item ['position'];
						
						$q = " UPDATE $table set position='{$update_pos['position']}' where id='{$update_pos['id']}'  ";
						
						$q = $this->core_model->dbQ ( $q );
						
					//$this->taxonomySave ( $update_pos );					

					}
				
				} else {
					
					$next_item = array ();
					
					$next_item ['parent_id'] = $cur_item ['parent_id'];
					
					$next_item ['taxonomy_type'] = $cur_item ['taxonomy_type'];
					
					$next_item ['position'] = $next_position;
					
					$next_item = $this->taxonomyGet ( $next_item );
					
					$next_item = $next_item [0];
					
					if ($next_item ['id'] != $cur_item ['id']) {
						
						$update_pos = array ();
						
						$update_pos ['id'] = $cur_item ['id'];
						
						$update_pos ['position'] = $next_item ['position'];
						
						//$this->taxonomySave ( $update_pos, true );						

						$q = " UPDATE $table set position='{$update_pos['position']}' where id='{$update_pos['id']}'  ";
						
						$q = $this->core_model->dbQ ( $q );
						
						$update_pos = array ();
						
						$update_pos ['id'] = $next_item ['id'];
						
						$update_pos ['position'] = $cur_item ['position'];
						
						//$this->taxonomySave ( $update_pos, true );						

						$q = " UPDATE $table set position='{$update_pos['position']}' where id='{$update_pos['id']}'  ";
						
						$q = $this->core_model->dbQ ( $q );
					
					}
				
				}
				
				$this->core_model->cleanCacheGroup ( 'taxonomy' );
				
				$this->core_model->cleanCacheGroup ( 'global' );
				
				//  $this->taxonomyFixPositionsForId ( $cur_item ['id'] );				

				//var_dump ( $item );				

				//exit ();				

				return false;
			
			}
		
		} else {
			
			return false;
		
		}
	
	}
	
	function taxonomyFixPositionsForId($id) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_taxonomy'];
		
		$item = $this->taxonomyGetSingleItemById ( $id );
		
		if (empty ( $item )) {
			
			return false;
		
		}
		
		$cur_item = $item;
		
		$items = array ();
		
		$items ['parent_id'] = $cur_item ['parent_id'];
		
		$items ['taxonomy_type'] = $cur_item ['taxonomy_type'];
		
		$orderby [0] = 'position';
		
		$orderby [1] = 'ASC';
		
		$items = $this->taxonomyGet ( $items, $orderby );
		
		$i = 1;
		
		//var_dump($items);		

		foreach ( $items as $item ) {
			
			$update_pos = array ();
			
			$update_pos ['id'] = $item ['id'];
			
			$update_pos ['position'] = $i;
			
			//$this->taxonomySave ( $update_pos, true );			

			$q = " UPDATE $table set position='{$update_pos['position']}' where id='{$update_pos['id']}'  ";
			
			$q = $this->core_model->dbQ ( $q );
			
			$i ++;
		
		}
		
		//  $this->core_model->cacheDelete ( 'cache_group', 'taxonomy' );		

		return $id;
	
	}
	
	function taxonomyGetMasterCategories($data = array()) {
		
		$get_master_categories = $data;
		
		$get_master_categories ['taxonomy_type'] = 'category';
		
		$get_master_categories ['parent_id'] = '0';
		
		if ($orderby == false) {
			
			$orderby [0] = 'position';
			
			$orderby [1] = 'ASC';
		
		}
		
		$items = $this->taxonomyGet ( $get_master_categories, $orderby );
		
		return $items;
	
	}
	
	/**	 * @desc Get a single row from the taxonomy_table by given ID and returns it as one dimensional array	 * @param int	 * @return array	 * @author      Peter Ivanov	 * @version 1.0	 * @since Version 1.0	 */
	
	function taxonomyGetSingleItemById($id) {
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$taxonomy_id = intval ( $id );
		$cache_group = 'taxonomy/' . $taxonomy_id;
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_taxonomy'];
		
		$id = intval ( $id );
		
		$q = " select * from $table where id = $id limit 0,1";
		
		$q = $this->core_model->dbQuery ( $q );
		
		$q = $q [0];
		
		if (! empty ( $q )) {
			
			$this->core_model->cacheWriteAndEncode ( $q, $function_cache_id, $cache_group );
			
			//return $to_cache;			

			return $q;
		
		} else {
			
			return false;
		
		}
	
	}
	
	function taxonomyCheckIfParamExistForSingleItemIdAndReturnVal($id, $the_param) {
		
		$data = $this->taxonomyGetSingleItemById ( $id );
		
		if ($data ["taxonomy_params"] != '') {
			
			//var_dump($data ["taxonomy_params"], $the_param);			

			$params = explode ( ',', $data ["taxonomy_params"] );
			
			$params = mb_trimArray ( $params );
			
			foreach ( $params as $item ) {
				
				$itemss = explode ( ':', $item );
				
				//var_dump( $item);				

				//foreach($itemss as $itm){				

				$item1 = mb_trim ( $itemss [0] );
				
				$item2 = trim ( $itemss [0] );
				
				if (mb_strtolower ( $item1 ) == mb_strtolower ( $the_param )) {
					return $itemss [1];
				
				}
				
				if (strtolower ( $item2 ) == strtolower ( $the_param )) {
					return $itemss [1];
				
				}
				
				if (md5 ( $item2 ) == md5 ( $the_param )) {
					return $itemss [1];
				
				}
				
			//}			

			}
		
		}
	
	}
	
	function taxonomyGetParentItemsAndReturnOnlyIds($id) {
		
		$items = $this->taxonomyGetParentItemsAndCache ( $id );
		
		if (! empty ( $items )) {
			
			$ids = array ();
			
			foreach ( $items as $item ) {
				
				$ids [] = $item ['id'];
			
			}
			
			$ids = array_unique ( $ids );
			
			return $ids;
		
		} else {
			
			return false;
		
		}
	
	}
	
	function taxonomyGetParentItemsAndCache($id) {
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$taxonomy_id = intval ( $id );
		$cache_group = 'taxonomy/' . $taxonomy_id;
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		$data = $this->taxonomyGetParentItems ( $id );
		
		$this->core_model->cacheWriteAndEncode ( $data, $function_cache_id, $cache_group );
		
		return $data;
	
	}
	
	function taxonomyGetParentItems($id) {
		
		//var_dump($id);		

		$data = array ();
		
		$data ['id'] = $id;
		
		$data = $this->taxonomyGet ( $data );
		
		$data = $data [0];
		
		$to_return = array ();
		
		if (empty ( $data )) {
			
			return false;
		
		}
		
		$to_return [] = $data;
		
		//$to_return		

		if (intval ( $data ['parent_id'] ) != 0) {
			
			$data1 = array ();
			
			$data1 ['id'] = $data ['parent_id'];
			
			$data1 = $this->taxonomyGet ( $data1 );
			
			foreach ( $data1 as $item ) {
				
				$to_return [] = $item;
				
				$more = $this->taxonomyGetParentItems ( $item ['id'] );
				
				if (! empty ( $more )) {
					
					foreach ( $more as $mo ) {
						
						$to_return [] = $mo;
					
					}
				
				}
			
			}
		
		}
		
		//$to_return = array_unique($to_return);		

		return $to_return;
	
	}
	
	function taxonomyGetChildrenItems($parent_id, $taxonomy_type = false, $orderby = false) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_taxonomy'];
		
		if ($orderby == false) {
			
			$orderby [0] = 'updated_on';
			
			$orderby [1] = 'DESC';
		
		}
		
		$data = array ();
		
		$data ['parent_id'] = $parent_id;
		
		if ($taxonomy_type != false) {
			
			$data ['taxonomy_type'] = $taxonomy_type;
		
		}
		
		$save = $this->taxonomyGet ( $data, $orderby, $no_limits = true );
		
		return $save;
	
	}
	
	function taxonomyGetChildrenItemsIdsRecursiveAndCache($parent_id, $type = false, $visible_on_frontend = false) {
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$taxonomy_id = intval ( $parent_id );
		$cache_group = 'taxonomy/' . $taxonomy_id;
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		} else {
			
			$to_cache = $this->taxonomyGetChildrenItemsIdsRecursive ( $parent_id, $type, $visible_on_frontend );
			
			$this->core_model->cacheWriteAndEncode ( $to_cache, $function_cache_id, $cache_group );
			
			return $to_cache;
		
		}
	
	}
	
	function taxonomyGetChildrenItemsIdsRecursive($parent_id, $type = false, $visible_on_frontend = false) {
		
		global $cms_db_tables;
		$taxonomy_id = intval ( $parent_id );
		$cache_group = 'taxonomy/' . $taxonomy_id;
		
		$table = $cms_db_tables ['table_taxonomy'];
		
		$table_content = $cms_db_tables ['table_content'];
		
		if ($orderby == false) {
			
			$orderby [0] = 'updated_on';
			
			$orderby [1] = 'DESC';
		
		}
		
		if (intval ( $parent_id ) == 0) {
			
			return false;
		
		}
		
		$data = array ();
		
		$data ['parent_id'] = $parent_id;
		
		if ($type != FALSE) {
			
			$data ['taxonomy_type'] = $type;
			
			$type_q = " and taxonomy_type='$type'   ";
		
		} else {
			
			$type_q = false;
		
		}
		
		if ($visible_on_frontend == true) {
			
			$visible_on_frontend_q = " and to_table_id in (select id from $table_content where visible_on_frontend='y') ";
		
		}
		
		//$save = $this->taxonomyGet ( $data = $data, $orderby = $orderby );		

		$cache_group = 'taxonomy/' . $parent_id;
		$q = " SELECT id,    parent_id from $table where parent_id= $parent_id   $type_q  $visible_on_frontend_q";
		//var_dump($cache_group);
		$q_cache_id = __FUNCTION__ . md5 ( $q );
		//var_dump($q_cache_id);
		$save = $this->core_model->dbQuery ( $q, $q_cache_id, $cache_group );
		
		//$save = $this->taxonomyGetSingleItemById ( $parent_id );
		if (empty ( $save )) {
			return false;
		}
		$to_return = array ();
		if (! empty ( $save )) {
			$to_return [] = $parent_id;
		}
		foreach ( $save as $item ) {
			$to_return [] = $item ['id'];
			$clidren = $this->taxonomyGetChildrenItemsIdsRecursive ( $item ['id'], $type, $visible_on_frontend );
			if (! empty ( $clidren )) {
				foreach ( $clidren as $temp ) {
					$to_return [] = $temp;
				}
			}
		}
		
		$to_return = array_unique ( $to_return );
		
		return $to_return;
	
	}
	
	function taxonomyDelete($id) {
		
		if (intval ( $id ) == 0) {
			
			return false;
		
		}
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_taxonomy'];
		
		$item_to_delete = array ();
		
		$item_to_delete ['id'] = $id;
		
		$item_to_delete = $this->taxonomyGet ( $item_to_delete );
		
		$item_to_delete = $item_to_delete [0];
		
		if (empty ( $item_to_delete )) {
			
			return false;
		
		}
		
		$new_parent = intval ( $item_to_delete ['parent_id'] );
		
		$old_parent = intval ( $item_to_delete ['id'] );
		
		$q = "UPDATE $table set parent_id = $new_parent where parent_id= $old_parent";
		
		$this->core_model->dbQ ( $q );
		
		$this->core_model->deleteDataById ( $table, $id );
		
		$this->core_model->cleanCacheGroup ( 'taxonomy' . DIRECTORY_SEPARATOR . $id );
		$this->core_model->cleanCacheGroup ( 'taxonomy' . DIRECTORY_SEPARATOR . $new_parent );
		$this->core_model->cleanCacheGroup ( 'taxonomy' . DIRECTORY_SEPARATOR . 'global' );
		$this->core_model->cleanCacheGroup ( 'taxonomy' . DIRECTORY_SEPARATOR . '0' );
		
		return true;
	
	}
	
	function taxonomyGetTaxonomyToTableIdsForTaxonomyRootIdAndCache($root, $visible_on_frontend = false, $non_recursive = false) {
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$taxonomy_id = intval ( $root );
		$cache_group = 'taxonomy/' . $taxonomy_id;
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		} else {
			
			$to_cache = $this->taxonomyGetTaxonomyToTableIdsForTaxonomyRootId ( $root, $visible_on_frontend, $non_recursive );
			
			$this->core_model->cacheWriteAndEncode ( $to_cache, $function_cache_id, $cache_group );
			
			return $to_cache;
		
		}
	
	}
	function taxonomyGetTaxonomyToTableIdsForTaxonomyRootId($root, $visible_on_frontend = false, $non_recursive = false) {
		
		if (intval ( $root ) == 0) {
			
			return false;
		
		}
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_taxonomy'];
		
		$table_content = $cms_db_tables ['table_content'];
		
		$ids = array ();
		
		//$ids [] = $root;		

		if ($visible_on_frontend == true) {
			
			$visible_on_frontend_q = " and to_table_id in (select id from $table_content where visible_on_frontend='y') ";
		
		}
		
		$root = intval ( $root );
		
		$data = array ();
		
		$data ['parent_id'] = $root;
		$root = intval ( $root );
		
		$q = " SELECT id, parent_id,to_table_id from $table where parent_id=$root $visible_on_frontend_q ";
		
		$taxonomies = $this->core_model->dbQuery ( $q, __FUNCTION__ . md5 ( $q ), 'taxonomy/' . $root );
		
		//var_dump($taxonomies);		

		if (! empty ( $taxonomies )) {
			
			foreach ( $taxonomies as $item ) {
				
				if (intval ( $item ['to_table_id'] ) != 0) {
					
					$ids [] = $item ['to_table_id'];
				}
				
				if ($non_recursive == false) {
					$next = $this->taxonomyGetTaxonomyToTableIdsForTaxonomyRootId ( $item ['id'], $visible_on_frontend );
					
					if (! empty ( $next )) {
						
						foreach ( $next as $n ) {
							
							if ($n != '') {
								
								$ids [] = $n;
							
							}
						
						}
					
					}
				}
			
			}
		
		}
		
		if (! empty ( $ids )) {
			
			$ids = array_unique ( $ids );
			
			asort ( $ids );
			
			return $ids;
		
		} else {
			
			return false;
		
		}
	
	}
	
	function taxonomyGetTaxonomyIdsForTaxonomyRootIdAndCache($root, $incliude_root = false) {
		
		//return false;		

		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		$root = intval ( $root );
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_group = 'taxonomy/' . $root;
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		} else {
			
			$to_cache = $this->taxonomyGetTaxonomyIdsForTaxonomyRootId ( $root, $incliude_root );
			
			$this->core_model->cacheWriteAndEncode ( $to_cache, $function_cache_id, $cache_group );
			
			return $to_cache;
		
		}
	
	}
	
	function taxonomyGetTaxonomyIdsForTaxonomyRootId($root, $incliude_root = false, $recursive = false) {
		
		if (intval ( $root ) == 0) {
			
			return false;
		
		}
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_taxonomy'];
		
		$ids = array ();
		
		if ($incliude_root == true) {
			
			$ids [] = $root;
		
		}
		
		$root = intval ( $root );
		$q = " select id from $table where parent_id = $root";
		
		$taxonomies = $this->core_model->dbQuery ( $q, $cache_id = __FUNCTION__ . md5 ( $q . $root . serialize ( $incliude_root ) ), $cache_group = 'taxonomy/' . $root );
		
		if (! empty ( $taxonomies )) {
			
			foreach ( $taxonomies as $item ) {
				
				if (intval ( $item ['id'] ) != 0) {
					
					$ids [] = $item ['id'];
				
				}
				
				if ($recursive == true) {
					$next = $this->taxonomyGetTaxonomyIdsForTaxonomyRootId ( $item ['id'], false, $recursive );
					
					if (! empty ( $next )) {
						
						foreach ( $next as $n ) {
							
							if ($n != '') {
								
								$ids [] = $n;
							
							}
						
						}
					
					}
				}
			}
		
		}
		
		if (! empty ( $ids )) {
			
			$ids = array_unique ( $ids );
			
			//asort ( $ids );			

			//			

			return $ids;
		
		} else {
			
			return false;
		
		}
	
	}
	
	function taxonomyGetParentIdsForId($id, $without_main_parrent = false) {
		
		if (intval ( $id ) == 0) {
			
			return FALSE;
		
		}
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_taxonomy'];
		
		$ids = array ();
		
		$data = array ();
		
		if ($without_main_parrent == true) {
			
			$with_main_parrent_q = " and parent_id<>0 ";
		
		} else {
			
			$with_main_parrent_q = false;
		
		}
		$id = intval ( $id );
		$q = " select id, parent_id  from $table where id = $id   $with_main_parrent_q ";
		
		$taxonomies = $this->core_model->dbQuery ( $q, $cache_id = __FUNCTION__ . md5 ( $q ), $cache_group = 'taxonomy/' . $id );
		
		//var_dump($q);		

		//  var_dump($taxonomies);		

		//  exit;		

		if (! empty ( $taxonomies )) {
			
			foreach ( $taxonomies as $item ) {
				
				if (intval ( $item ['id'] ) != 0) {
					
					$ids [] = $item ['parent_id'];
				
				}
				
				$next = $this->taxonomyGetParentIdsForId ( $item ['parent_id'], $without_main_parrent );
				
				if (! empty ( $next )) {
					
					foreach ( $next as $n ) {
						
						if ($n != '') {
							
							$ids [] = $n;
						
						}
					
					}
				
				}
			
			}
		
		}
		
		if (! empty ( $ids )) {
			
			$ids = array_unique ( $ids );
			
			//asort ( $ids );			

			//			

			return $ids;
		
		} else {
			
			return false;
		
		}
	
	}
	
	function taxonomyGetCategoriesForContentId($content_id) {
		
		if (intval ( $content_id ) == 0) {
			
			return false;
		
		}
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		$content_id = intval ( $content_id );
		$cache_group = 'content/' . $content_id;
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		$cat_ids = $this->taxonomyGetTaxonomyIdsForContentId ( $content_id, $taxonomy_type = 'categories' );
		
		$to_return = array ();
		
		foreach ( $cat_ids as $item ) {
			
			$cat = $this->taxonomyGetSingleItemById ( $item );
			
			$to_return [] = $cat;
		
		}
		
		$this->core_model->cacheWriteAndEncode ( $to_return, $function_cache_id, $cache_group );
		
		return $to_return;
	
	}
	
	function taxonomyGetTaxonomyIdsForContentId($content_id, $taxonomy_type = false) {
		
		if (intval ( $content_id ) == 0) {
			
			return false;
		
		}
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		$content_id = intval ( $content_id );
		$cache_group = 'content/' . $content_id;
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_taxonomy'];
		
		$data = array ();
		
		$data ['to_table'] = 'table_content';
		
		$data ['to_table_id'] = $content_id;
		$taxonomy_type_q = false;
		if ($taxonomy_type == 'categories') {
			
			$data ['taxonomy_type'] = 'category_item';
			$taxonomy_type_q = "and taxonomy_type = 'category_item' ";
		
		}
		
		if ($taxonomy_type == 'tags') {
			
			$data ['taxonomy_type'] = 'tag_item';
			$taxonomy_type_q = "and taxonomy_type = 'tag_item' ";
		}
		
		$q = "select parent_id from $table where  to_table='to_table' and to_table_id=$content_id $taxonomy_type_q ";
		$data = $this->core_model->dbQuery ( $q, __FUNCTION__ . md5 ( $q ), $cache_group = 'content/' . $content_id );
		// var_dump ( $data );		

		if (! empty ( $data )) {
			
			$results = array ();
			
			foreach ( $data as $item ) {
				
				$results [] = $item ['parent_id'];
			
			}
			
			$results = array_unique ( $results );
		
		}
		
		$this->core_model->cacheWriteAndEncode ( $results, $function_cache_id, $cache_group );
		
		return $results;
		
	//var_dump ( $data );	

	}
	
	function contentActiveCategoriesForToTableId($to_table_id = false) {
	
	}
	
	function contentGetRecomendedPosts($post_id, $how_much = 5) {
		
		$function_cache_id = serialize ( $post_id ) . serialize ( $how_much );
		
		$function_cache_id = __FUNCTION__ . md5 ( __FUNCTION__ . $function_cache_id );
		
		$content_id = intval ( $post_id );
		$cache_group = 'content/' . $content_id;
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_content'];
		
		$post = $this->contentGetByIdAndCache ( $post_id );
		
		if (empty ( $post )) {
			
			return false;
		
		} else {
			
			//  var_dump ( $post ["content_title"] );			

			$post ["content_title"] = str_ireplace ( '(', '', $post ["content_title"] );
			
			$post ["content_title"] = str_ireplace ( ')', '', $post ["content_title"] );
			
			$post ["content_title"] = str_ireplace ( '`', '', $post ["content_title"] );
			
			$post ["content_title"] = str_ireplace ( '"', '', $post ["content_title"] );
			
			$post ["content_title"] = str_ireplace ( '\'', '', $post ["content_title"] );
			
			$keywords_array = explode ( ' ', $post ["content_title"] );
			
			$keywords_array_new = array ();
			
			foreach ( $keywords_array as $kw ) {
				
				if (mb_strlen ( $kw ) > 4) {
					
					$keywords_array_new [] = $kw;
				
				}
			
			}
			
			$keywords_array = $keywords_array_new;
			
			$keywords_array_original = $keywords_array;
			
			$the_results_to_return = array ();
			
			for($i = 0; $i <= count ( $keywords_array ) - 1; $i ++) {
				
				//echo $i;				

				$fruit = array_pop ( $keywords_array );
				
				$search_string = implode ( ' ', $keywords_array );
				
				$search_string = str_ireplace ( '-', ' ', $search_string );
				
				$search_string = str_ireplace ( '  ', ' ', $search_string );
				
				$search_string = str_ireplace ( ' ', '+', $search_string );
				
				$only_in_those_table_fields = array ();
				
				$only_in_those_table_fields [] = 'content_title';
				
				if ($search_string != '') {
					
					$search_in_content = $this->core_model->dbRegexpSearch ( $table, $search_string, $only_in_ids = false, $only_in_those_table_fields = $only_in_those_table_fields );
					
					if (! empty ( $search_in_content )) {
						
						foreach ( $search_in_content as $item ) {
							
							$the_results_to_return [] = $item;
						
						}
					
					}
				
				}
			
			}
			
			$keywords_array = $keywords_array_original;
			
			shuffle ( $keywords_array );
			
			for($i = 0; $i <= count ( $keywords_array ) - 1; $i ++) {
				
				$fruit = array_pop ( $keywords_array );
				
				$search_string = implode ( ' ', $keywords_array );
				
				$search_string = str_ireplace ( '-', ' ', $search_string );
				
				$search_string = str_ireplace ( '  ', ' ', $search_string );
				
				$search_string = str_ireplace ( ' ', '+', $search_string );
				
				$only_in_those_table_fields = array ();
				
				$only_in_those_table_fields [] = 'content_title';
				
				if ($search_string != '') {
					
					$search_in_content = $this->core_model->dbRegexpSearch ( $table, $search_string, $only_in_ids = false, $only_in_those_table_fields = $only_in_those_table_fields );
					
					if (! empty ( $search_in_content )) {
						
						foreach ( $search_in_content as $item ) {
							
							$the_results_to_return [] = $item;
						
						}
					
					}
				
				}
			
			}
			
			$keywords_array = $keywords_array_original;
			
			shuffle ( $keywords_array );
			
			for($i = 0; $i <= count ( $keywords_array ) - 1; $i ++) {
				
				$fruit = array_pop ( $keywords_array );
				
				$search_string = implode ( ' ', $keywords_array );
				
				$search_string = str_ireplace ( '-', ' ', $search_string );
				
				$search_string = str_ireplace ( '  ', ' ', $search_string );
				
				$search_string = str_ireplace ( ' ', '+', $search_string );
				
				$only_in_those_table_fields = array ();
				
				$only_in_those_table_fields [] = 'content_title';
				
				if ($search_string != '') {
					
					$search_in_content = $this->core_model->dbRegexpSearch ( $table, $search_string, $only_in_ids = false, $only_in_those_table_fields = $only_in_those_table_fields );
					
					if (! empty ( $search_in_content )) {
						
						foreach ( $search_in_content as $item ) {
							
							$the_results_to_return [] = $item;
						
						}
					
					}
				
				}
			
			}
			
			$keywords_array = $keywords_array_original;
			
			shuffle ( $keywords_array );
			
			for($i = 0; $i <= count ( $keywords_array ) - 1; $i ++) {
				
				$fruit = array_pop ( $keywords_array );
				
				$search_string = implode ( ' ', $keywords_array );
				
				$search_string = str_ireplace ( '-', ' ', $search_string );
				
				$search_string = str_ireplace ( '  ', ' ', $search_string );
				
				$search_string = str_ireplace ( ' ', '+', $search_string );
				
				$only_in_those_table_fields = array ();
				
				$only_in_those_table_fields [] = 'content_title';
				
				if ($search_string != '') {
					
					$search_in_content = $this->core_model->dbRegexpSearch ( $table, $search_string, $only_in_ids = false, $only_in_those_table_fields = $only_in_those_table_fields );
					
					if (! empty ( $search_in_content )) {
						
						foreach ( $search_in_content as $item ) {
							
							$the_results_to_return [] = $item;
						
						}
					
					}
				
				}
			
			}
			
			$keywords_array = $keywords_array_original;
			
			shuffle ( $keywords_array );
			
			for($i = 0; $i <= count ( $keywords_array ) - 1; $i ++) {
				
				$fruit = array_pop ( $keywords_array );
				
				$search_string = implode ( ' ', $keywords_array );
				
				$search_string = str_ireplace ( '-', ' ', $search_string );
				
				$search_string = str_ireplace ( '  ', ' ', $search_string );
				
				$search_string = str_ireplace ( ' ', '+', $search_string );
				
				$only_in_those_table_fields = array ();
				
				$only_in_those_table_fields [] = 'content_title';
				
				if ($search_string != '') {
					
					$search_in_content = $this->core_model->dbRegexpSearch ( $table, $search_string, $only_in_ids = false, $only_in_those_table_fields = $only_in_those_table_fields );
					
					if (! empty ( $search_in_content )) {
						
						foreach ( $search_in_content as $item ) {
							
							$the_results_to_return [] = $item;
						
						}
					
					}
				
				}
			
			}
			
		//}		

		//var_dump($post["content_body_nohtml"]);		

		}
		
		$the_results_to_return_revalency = array ();
		
		//var_dump ( $the_results_to_return );		

		foreach ( $the_results_to_return as $item ) {
			
			if (intval ( $item ) != intval ( $post_id )) {
				
				//var_dump ( $item ['content_title'] );				

				$the_results_to_return_revalency [$item] = intval ( $the_results_to_return_revalency [$item] ) + 1;
			
			}
		
		}
		
		arsort ( $the_results_to_return_revalency );
		
		$posts = array ();
		
		$i = 1;
		
		foreach ( $the_results_to_return_revalency as $k => $v ) {
			
			if ($i > $how_much) {
			
			} else {
				
				$post = $this->contentGetByIdAndCache ( $k );
				
				$posts [] = $post;
			
			}
			
			$i ++;
		
		}
		
		if ($posts != '') {
			
			$this->core_model->cacheWriteAndEncode ( $posts, $function_cache_id, $cache_group );
			
			return $posts;
		
		} else {
			
			return false;
		
		}
		
		return $posts;
		
	//var_dump ( $the_results_to_return_revalency );	

	}
	
	function taxonomyGetAvailableTags($to_table = false, $to_table_id = false) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_taxonomy'];
		
		if ($to_table != false) {
			
			$q1 = " and to_table='$to_table'   ";
		
		}
		
		if ($to_table_id != false) {
			
			$q2 = " and to_table_id='$to_table_id'   ";
		
		}
		
		$q = "select taxonomy_value from $table where taxonomy_type='tag'  $q1  $q2 group by taxonomy_value";
		
		$cache_id = __FUNCTION__ . md5 ( $q );
		
		$q = $this->core_model->dbQuery ( $q, $cache_id, 'taxonomy/global' );
		
		$to_return = array ();
		
		if (! empty ( $q )) {
			
			foreach ( $q as $item ) {
				
				$to_return [] = $item ['taxonomy_value'];
			
			}
			
			return $to_return;
		
		} else {
			
			return false;
		
		}
	
	}
	
	function optionsSave($data) {
		
		$data = $this->core_model->optionsSave ( $data );
		
		$this->core_model->cleanCacheGroup ( 'options' );
		
		$this->core_model->cacheDeleteAll ();
		
		return true;
		
	/*global $cms_db_tables;        $table = $cms_db_tables ['table_options'];        $save = $this->core_model->saveData ( $table, $data );        $this->core_model->cacheDelete ( 'cache_group', 'options' );        return true;*/
	
	}
	
	function optionsGet($data) {
		
		$data = $this->core_model->optionsGet ( $data );
		
		return $data;
		
	/*global $cms_db_tables;        $table = $cms_db_tables ['table_options'];        if ($orderby == false) {        $orderby [0] = 'created_on';        $orderby [1] = 'DESC';        }        $save = $this->core_model->getDbData ( $table, $data, $limit = false, $offset = false, $orderby, $cache_group = 'options' );        return $save;*/
	
	}
	
	function optionsGetByKey($key) {
		
		$data = $this->core_model->optionsGetByKey ( $key );
		
		return $data;
		
	/*global $cms_db_tables;        $table = $cms_db_tables ['table_options'];        if ($orderby == false) {        $orderby [0] = 'created_on';        $orderby [1] = 'DESC';        }        $data = array ( );        $data ['option_key'] = $key;        $get = $this->core_model->getDbData ( $table, $data, $limit = false, $offset = false, $orderby, $cache_group = 'options' );        $get = $get [0] ['option_value'];        return $get;*/
	
	}
	
	function content_helpers_getPagesAsUlTree($content_parent = 0, $link = false, $actve_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_content'];
		
		if ($content_parent == false) {
			
			$content_parent = intval ( 0 );
		
		}
		
		$sql = "SELECT * from $table where content_parent=$content_parent and content_type='page'  ";
		
		$q = $this->core_model->dbQuery ( $sql );
		
		$result = $q;
		
		if (! empty ( $result )) {
			
			//$output = "<ul>";			

			print "<ul>";
			
			foreach ( $result as $item ) {
				
				$output = $output . $item ['content_title'];
				
				$content_type_li_class = false;
				
				if ($item ['is_home'] != 'y') {
					
					switch ($item ['content_subtype']) {
						
						case 'blog_section' :
							
							$content_type_li_class = 'is_category';
							
							break;
						
						case 'module' :
							
							$content_type_li_class = 'is_module';
							
							break;
						
						default :
							
							$content_type_li_class = 'is_page';
							
							break;
					
					}
				
				} else {
					
					$content_type_li_class = 'is_home';
				
				}
				
				print "<li class='$content_type_li_class'>";
				
				if ($link != false) {
					
					$to_print = false;
					
					$to_print = str_ireplace ( '{id}', $item ['id'], $link );
					
					$to_print = str_ireplace ( '{content_title}', $item ['content_title'], $to_print );
					
					if (is_array ( $actve_ids ) == true) {
						
						$is_there_active_ids = false;
						
						foreach ( $actve_ids as $active_id ) {
							
							if (strval ( $item ['id'] ) == strval ( $active_id )) {
								
								$is_there_active_ids = true;
								
								$to_print = str_ireplace ( '{active_code}', $active_code, $to_print );
							
							}
						
						}
						
						if ($is_there_active_ids == false) {
							
							$to_print = str_ireplace ( '{active_code}', '', $to_print );
						
						}
					
					} else {
						
						$to_print = str_ireplace ( '{active_code}', '', $to_print );
					
					}
					
					if (is_array ( $remove_ids ) == true) {
						
						if (in_array ( $item ['id'], $remove_ids )) {
							
							if ($removed_ids_code == false) {
								
								$to_print = false;
							
							} else {
								
								$to_print = str_ireplace ( '{removed_ids_code}', $removed_ids_code, $to_print );
							
							}
						
						} else {
							
							$to_print = str_ireplace ( '{removed_ids_code}', '', $to_print );
						
						}
					
					}
					
					print $to_print;
				
				} else {
					
					print $item ['content_title'];
				
				}
				
				$children = $this->content_helpers_getPagesAsUlTree ( $item ['id'], $link, $actve_ids, $active_code, $remove_ids, $removed_ids_code );
				
				print "</li>";
			
			}
			
			print "</ul>";
		
		} else {
		
		}
	
	}
	
	function content_helpers_getCaregoriesUlTreeAndCache($content_parent, $link = false, $actve_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false, $content_type = false, $add_ids = false, $orderby = false, $only_with_content = false) {
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_group = 'taxonomy/global';
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		} else {
			
			$to_cache = $this->content_helpers_getCaregoriesUlTree ( $content_parent, $link, $actve_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name, $include_first, $content_type, $add_ids, $orderby, $only_with_content );
			
			$this->core_model->cacheWriteAndEncode ( $to_cache, $function_cache_id, $cache_group );
			
			return $to_cache;
		
		}
	
	}
	
	/**`	 * @desc Prints the selected categories as an <UL> tree, you might pass several options for more flexibility	 * @param array	 * @param boolean	 * @author      Peter Ivanov	 * @version 1.0	 * @since Version 1.0	 */
	
	function content_helpers_getCaregoriesUlTree($content_parent, $link = false, $actve_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false, $content_type = false, $li_class_name = false, $add_ids = false, $orderby = false, $only_with_content = false, $visible_on_frontend = false) {
		
		global $cms_db_tables;
		
		$table = $table_taxonomy = $cms_db_tables ['table_taxonomy'];
		
		$table_content = $cms_db_tables ['table_content'];
		
		if ($content_parent == false) {
			
			$content_parent = intval ( 0 );
			
			$include_first = false;
		
		} else {
			
			$content_parent = intval ( $content_parent );
		
		}
		
		if (! is_array ( $orderby )) {
			
			$orderby [0] = 'position';
			
			$orderby [1] = 'ASC';
		
		}
		
		//var_dump($orderby);		

		//exit;		

		if (! empty ( $remove_ids )) {
			
			$remove_ids_q = implode ( ',', $remove_ids );
			
			$remove_ids_q = " and id not in ($remove_ids_q) ";
		
		} else {
			
			$remove_ids_q = false;
		
		}
		
		if (! empty ( $add_ids )) {
			
			$add_ids_q = implode ( ',', $add_ids );
			
			$add_ids_q = " and id in ($add_ids_q) ";
		
		} else {
			
			$add_ids_q = false;
		
		}
		
		//p($content_type,1);		

		$content_type = addslashes ( $content_type );
		
		if ($content_type == false) {
			
			if ($include_first == true) {
				
				$sql = "SELECT * from $table where id=$content_parent   $remove_ids_q $add_ids_q order by {$orderby [0]}  {$orderby [1]}  ";
			
			} else {
				
				$sql = "SELECT * from $table where parent_id=$content_parent and taxonomy_type='category'   $remove_ids_q $add_ids_q order by {$orderby [0]}  {$orderby [1]}   ";
			
			}
		
		} else {
			
			if ($include_first == true) {
				
				$sql = "SELECT * from $table where id=$content_parent and (taxonomy_content_type='$content_type' or taxonomy_content_type='inherit' )  $remove_ids_q $add_ids_q order by {$orderby [0]}  {$orderby [1]}  ";
			
			} else {
				
				$sql = "SELECT * from $table where parent_id=$content_parent and taxonomy_type='category' and (taxonomy_content_type='$content_type' or taxonomy_content_type='inherit' )   $remove_ids_q  $add_ids_q order by {$orderby [0]}  {$orderby [1]}   ";
			
			}
		
		}
		
		//  p($sql);		

		$children_of_the_main_parent = $this->taxonomyGetChildrenItemsIdsRecursiveAndCache ( $content_parent, $type = 'category_item', $visible_on_frontend );
		
		//  p($children_of_the_main_parent);		

		$q = $this->core_model->dbQuery ( $sql, $cache_id = 'content_helpers_getCaregoriesUlTree_parent_cats_q_' . md5 ( $sql ), $cache_group = 'taxonomy/' . $content_parent );
		
		$result = $q;
		
		//		

		$only_with_content2 = $only_with_content;
		
		$do_not_show_next = false;
		
		$chosen_categories_array = array ();
		
		if (! empty ( $result )) {
			
			//$output = "<ul>";			

			$i = 0;
			
			foreach ( $result as $item ) {
				
				$do_not_show = false;
				
				if ($only_with_content == true) {
					
					$check_in_table_content = false;
					
					if (is_array ( $only_with_content ) and ! empty ( $only_with_content )) {
						
						$content_ids_of_the_1_parent = $this->taxonomyGetTaxonomyToTableIdsForTaxonomyRootIdAndCache ( $only_with_content [0], $visible_on_frontend, $non_recursive = true );
						
						$content_ids_of_the_1_parent_o = $content_ids_of_the_1_parent;
						
						//p($content_ids_of_the_1_parent);						

						//  and visible_on_frontend = 'y'						

						// p($item);						

						if (! empty ( $content_ids_of_the_1_parent )) {
							
							$only_with_content3 = array ();
							
							$chosen_categories_array = array ();
							
							foreach ( $only_with_content2 as $only_with_content2_i ) {
								
								$only_with_content2_i = str_ireplace ( '{id}', $item ['id'], $only_with_content2_i );
								
								$only_with_content2_i = intval ( $only_with_content2_i );
								
								$chosen_categories_array [] = $only_with_content2_i;
								
							//$parentz = $this->taxonomyGetParentIdsForId ( $only_with_content2_i, true );							

							//if (! empty ( $parentz )) {							

							//  $chosen_categories_array = array_merge ( $chosen_categories_array, $parentz );							

							//}							

							//p ( $parentz );							

							//  $content_ids_of_the_next_parent = $this->taxonomyGetTaxonomyToTableIdsForTaxonomyRootId ( $only_with_content2_i, $visible_on_frontend );							

							//$content_ids_of_the_1_parent = array_unique ( $content_ids_of_the_1_parent );							

							}
							
							//$chosen_categories_array [] = $content_parent;							

							//p($content_ids_of_the_1_parent);							

							if (count ( $chosen_categories_array ) > 1) {
								
							//array_shift ( $chosen_categories_array );							

							}
							
							//  p($chosen_categories_array);							

							$chosen_categories_array_i = implode ( ',', $chosen_categories_array );
							
							$children_of_the_next_parent = $this->taxonomyGetTaxonomyToTableIdsForTaxonomyRootIdAndCache ( $only_with_content [0], $type = 'category_item', $visible_on_frontend );
							
							$children_of_the_next_parent_i = implode ( ',', $children_of_the_next_parent );
							
							$children_of_the_next_parent_qty = count ( $chosen_categories_array );
							
							$q = " select id , count(to_table_id) as qty from $table_taxonomy where                             to_table_id in($children_of_the_next_parent_i)                              and parent_id  IN ($chosen_categories_array_i)                               group by to_table_id                         ";
							
							//  p ( $q );							

							$q = $this->core_model->dbQuery ( $q, __FUNCTION__ . md5 ( $q ), 'taxonomy/global' );
							
							// p ( $q );							

							$total_count_array = array ();
							
							if (! empty ( $q )) {
								
								foreach ( $q as $q1 ) {
									
									//  p ( $q1 );									

									$qty = intval ( $q1 ['qty'] );
									
									//print 									

									//  print $qty .'---------'.$children_of_the_next_parent_qty. '<br>';									

									if (($children_of_the_next_parent_qty) == $qty) {
										
										//  print 'asdasd';										

										$total_count_array [] = $q1;
									
									}
								
								}
							
							}
							
							//$q = intval ( $q [0] ['qty'] );							

							$results_count = count ( $total_count_array );
							
							//  var_dump( $results_count);							

							//p ( $q );							

							//p($children_of_the_next_parent);							

							//p($chosen_categories_array);							

							/*  $posts_data = array ();                            $posts_data ['selected_categories'] = $chosen_categories_array;                            $posts_data ['strict_category_selection'] = true;                            if ($visible_on_frontend == true) {                                $posts_data ['visible_on_frontend'] = 'y';                            }                            $results_count = 1;                            $posts_data = $this->getContentAndCache($posts_data, $orderby = false, $limit = array(0,1), $count_only = false, $short_data = true, $only_fields = false) ;                            */
							
							$content_ids_of_the_1_parent_i = implode ( ',', $children_of_the_main_parent );
							
							$chosen_categories_array_i = implode ( ',', $chosen_categories_array );
							
							/*  $q = " select  count(*) as qty from $table_taxonomy where                    to_table= 'table_content'                    and to_table_id in($content_ids_of_the_1_parent_i)                    and content_type = 'post'                    and parent_id  IN ($chosen_categories_array_i)                    and taxonomy_type = 'category_item'                                 ;";*/
							
							//       print $q ; 							

							/*                            $q = " select  to_table_id,  count(parent_id) as parent_id123  from $table_taxonomy where                    to_table= 'table_content'                                    and content_type = 'post'                    and parent_id  IN ($chosen_categories_array_i)                    and taxonomy_type = 'category_item'             group by to_table_id                    ;";                            //$q = $this->core_model->dbQuery ( $q, md5 ( $q ), 'taxonomy' );                            $qty123 = array();                            if (! empty ( $q )) {                                //$q = intval ( $q [0] ['qty'] );                            //var_dump( $q) ;                            //  $results_count = $q;                            foreach($q as $q1){                                if(intval($q1['parent_id123']) == count($chosen_categories_array) ){                                //  var_dump( $q) ;                                //$results_count = 1;                                //$qty123[] = $q1;                                }                                                           }                                                                                             } else {                                $results_count = 0;                            }*/
							
							//  print "<hr>";							

							/*  if ($q == count ( $categories )) {                        $strict_ids [] = $id;                    } else {                        $strict_ids [] = 'No such id';                    }                 */
							
							//  $posts_data = $this->contentGetByParams ( $posts_data );							

							//      p($posts_data);							

							/*if (! empty ( $posts_data ['posts'] )) {                                $results_count = count ( $posts_data ['posts'] );                                //print $results_count;                                                        } else {                                $results_count = 0;                            }*/
							
							$result [$i] ['content_count'] = $results_count;
							
							$item ['content_count'] = $results_count;
							
						/*$only_with_content3 [] = $item ['id'];                                                        $only_with_content3 = array_unique ( $only_with_content3 );                            $only_with_content3_i = implode ( ',', $only_with_content3 );                            $content_ids_of_the_main_parent_i = implode ( ',', $content_ids_of_the_1_parent );                                                        if ($visible_on_frontend == true) {                                $visible_on_frontend_q = " and to_table_id in (select id from $table_content where visible_on_frontend='y') ";                                                        }                                                        $q_1 = "select count(*) as qty from $table where                        content_type = 'post'                         and parent_id in ({$item ['id']})                          and to_table_id in ($content_ids_of_the_main_parent_i)                                                  $visible_on_frontend_q                                                                          and to_table_id is not null                          group by to_table_id                        ";                                                        $q_1 = $this->core_model->dbQuery ( $q_1, $cache_id = basename ( __FILE__, '.php' ) . __LINE__ . md5 ( $q_1 ), $cache_group = 'taxonomy' );                                                        $results_count = intval ( $q_1 [0] ['qty'] );*/
						
						} else {
							
							$results_count = 0;
						
						}
						
						// var_dump($results_count);						

						$result [$i] ['content_count'] = $results_count;
						
						$item ['content_count'] = $results_count;
					
					} else {
						
						//  var_dump($results_count);						

						/*      if ($results_count != 0) {                            $childern_content = $this->taxonomyGetChildrenItemsIdsRecursiveAndCache ( $item ['parent_id'], $type = 'category_item', $visible_on_frontend );                        } else {                            $childern_content = false;                        }                                                                         if (! empty ( $childern_content )) {                                                        $do_not_show = false;                        } else {                            $do_not_show = false;                        }*/
						
						//$result [$i] ['content_count'] = $results_count;						

						//$item ['content_count'] = $results_count;						

						$do_not_show = false;
					
					}
				
				}
				
				$i ++;
			
			}
			
			if ($content_parent == 2163) {
				
			//p ( $do_not_show );			

			}
			
			//var_dump($do_not_show);			

			if ($do_not_show == false) {
				
				$print1 = false;
				
				if ($ul_class_name == false) {
					
					$print1 = "<ul>";
				
				} else {
					
					$print1 = "<ul class='$ul_class_name'>";
				
				}
				
				print $print1;
				
				foreach ( $result as $item ) {
					
					if ($only_with_content == true) {
						
						$do_not_show = false;
						
						$check_in_table_content = false;
						
						$childern_content = $this->taxonomyGetChildrenItemsIdsRecursiveAndCache ( $item ['id'], $type = 'category_item', $visible_on_frontend );
						
						//$childern_content2 = " SELECT  "						

						//p($item);						

						if (! empty ( $childern_content )) {
							
							//p($childern_content);							

							//var_dump($childern_content);							

							//exit;							

							$do_not_show = false;
							
						/*if (! empty ( $chosen_categories_array )) {                                                        $posts_data = array ();                            $posts_data ['selected_categories'] = $chosen_categories_array;                            $posts_data ['strict_category_selection'] = true;                            if ($visible_on_frontend == true) {                                $posts_data ['visible_on_frontend'] = 'y';                            }                            //$posts_data = $this->contentGetByParams ( $posts_data );                            //p($posts_data);                                                        //$posts_data = $this->getContentAndCache($posts_data, $orderby = false, $limit = array(0,1), $count_only = false, $short_data = true, $only_fields = false) ;                            $do_not_show = false;                            //var_Dump($posts_data);                            if (! empty ( $posts_data ['posts'] )) {                                //$results_count = count ( $posts_data ['posts'] );                            //$do_not_show = false;                            } else {                                //$do_not_show = true;                            }                        }*/
						
						} else {
							
							$do_not_show = true;
						
						}
					
					} else {
						
						$do_not_show = false;
					
					}
					
					//  p($do_not_show);					

					if ($do_not_show == false) {
						
						$output = $output . $item ['taxonomy_value'];
						
						if ($li_class_name == false) {
							
							print "<li>";
						
						} else {
							
							print "<li class='$li_class_name'>";
						
						}
					
					}
					
					if ($do_not_show == false) {
						
						if ($link != false) {
							
							$to_print = false;
							
							$to_print = str_ireplace ( '{id}', $item ['id'], $link );
							
							$to_print = str_ireplace ( '{taxonomy_url}', $this->taxonomyGetUrlForTaxonomyIdAndCache ( $item ['id'] ), $to_print );
							
							$to_print = str_ireplace ( '{taxonomy_value}', $item ['taxonomy_value'], $to_print );
							
							$to_print = str_ireplace ( '{taxonomy_value2}', $item ['taxonomy_value2'], $to_print );
							
							$to_print = str_ireplace ( '{taxonomy_value3}', $item ['taxonomy_value3'], $to_print );
							
							$to_print = str_ireplace ( '{taxonomy_content_type}', trim ( $item ['taxonomy_content_type'] ), $to_print );
							
							$to_print = str_ireplace ( '{content_count}', $item ['content_count'], $to_print );
							
							if (intval ( $item ['content_count'] ) == 0) {
								
								$to_print = str_ireplace ( '{empty_or_full}', 'empty', $to_print );
							
							} else {
								
								$to_print = str_ireplace ( '{empty_or_full}', 'full', $to_print );
							
							}
							
							//print $item ['content_count'];							

							if (is_array ( $actve_ids ) == true) {
								
								if (in_array ( $item ['id'], $actve_ids )) {
									
									$to_print = str_ireplace ( '{active_code}', $active_code, $to_print );
								
								} else {
									
									$to_print = str_ireplace ( '{active_code}', '', $to_print );
								
								}
							
							} else {
								
								$to_print = str_ireplace ( '{active_code}', '', $to_print );
							
							}
							
							//  p($item);							

							if (is_array ( $remove_ids ) == true) {
								
								if (in_array ( $item ['id'], $remove_ids )) {
									
									if ($removed_ids_code == false) {
										
										$to_print = false;
									
									} else {
										
										$to_print = str_ireplace ( '{removed_ids_code}', $removed_ids_code, $to_print );
									
									}
								
								} else {
									
									$to_print = str_ireplace ( '{removed_ids_code}', '', $to_print );
								
								}
							
							}
							
							if (strval ( $to_print ) == '') {
								
								print $item ['taxonomy_value'];
							
							} else {
								
								print $to_print;
							
							}
							
						//p($item);						

						//  var_dump ($to_print);						

						} else {
							
							print $item ['taxonomy_value'];
						
						}
						
						//$content_parent, $link = false, $actve_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false, $content_type = false, $li_class_name = false) {						

						//p( $item);						

						// $li_class_name = false, $add_ids = false, $orderby = false, $only_with_content = false						

						$children = $this->content_helpers_getCaregoriesUlTree ( $item ['id'], $link, $actve_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name, false, $content_type, $li_class_name, $add_ids, $orderby, $only_with_content, $visible_on_frontend );
						
						print "</li>";
					
					}
				
				}
				
				print "</ul>";
			
			}
		
		} else {
		
		}
	
	}
	
	function content_helpers_IsContentInMenu($content_id, $menu_id) {
		
		global $cms_db_tables;
		
		//$table = $cms_db_tables ['table_content'];		

		if (intval ( $content_id ) == 0) {
			
			return false;
		
		}
		
		//  var_dump ( $content_id, $menu_id );		

		$table = TABLE_PREFIX . 'menus';
		
		$orderby [0] = 'updated_on';
		
		$orderby [1] = 'DESC';
		
		$data = array ();
		
		$data ['content_id'] = $content_id;
		
		$data ['item_parent'] = $menu_id;
		
		$get = $this->core_model->getDbData ( $table, $data, $limit = false, $offset = false, $orderby, $cache_group = 'menus', false );
		
		$get = $get [0];
		
		if (! empty ( $get )) {
			
			return true;
		
		} else {
			
			return false;
		
		}
	
	}
	
	function commentsDeleteById($id) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_comments'];
		
		$this->core_model->deleteDataById ( $table, $id, $delete_cache_group = 'comments' );
		
		return true;
	
	}
	
	function commentsSave($data) {
		
		global $cms_db_tables;
		
		$data = stripFromArray ( $data );
		
		$data = htmlspecialchars_deep ( $data );
		
		$table = $cms_db_tables ['table_comments'];
		
		$data_to_save_options ['delete_cache_groups'] = array ('comments' );
		
		$id = $this->core_model->saveData ( $table, $data, $data_to_save_options );
		
		return $id;
	
	}
	
	function commentApprove($id) {
		
		global $cms_db_tables;
		
		$data ['id'] = $id;
		
		$data ['is_moderated'] = 'y';
		
		$this->commentsSave ( $data );
		
		return $id;
	
	}
	
	function commentGetById($id) {
		
		$data ['id'] = intval ( $id );
		
		$real_comments = $this->commentsGet ( $data );
		
		if (! empty ( $real_comments )) {
			
			return $real_comments [0];
		
		}
	
	}
	
	function commentsGet($data, $limit = false, $count_only = false, $orderby = false) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_comments'];
		
		if (empty ( $orderby )) {
			
			$orderby [0] = 'updated_on';
			
			$orderby [1] = 'DESC';
		
		} else {
			
			$orderby = $orderby;
		
		}
		
		$get = $this->core_model->getDbData ( $table, $data, $limit = $limit, $offset = false, $orderby, $cache_group = 'comments', false, $ids = false, $count_only );
		
		if ($count_only) {
			
			return $get;
		
		}
		
		$real_comments = array ();
		
		if (empty ( $get )) {
			
			return false;
		
		}
		
		foreach ( $get as $comment ) {
			
			if (! empty ( $cms_db_tables )) {
				
				foreach ( $cms_db_tables as $k => $v ) {
					
					//var_dump($k, $v);					

					if (strtolower ( $comment ['to_table'] ) == strtolower ( $k )) {
						
						//  var_dump($v);						

						$id = $comment ['to_table_id'];
						
						$real_comments [] = $comment;
						
					/*$id = intval ( $id );                        $check = "select count(*) as check_if_exist from $v where id=$id";                        $q = $this->core_model->dbQuery ( $check );                        $check = intval ( $q [0] ['check_if_exist'] );                        if ($check == 0) {                            $del = "delete from $table where id={$comment['id']}";                            //var_dump($del );                            $q = $this->core_model->dbQ ( $del );                        } else {                            $real_comments [] = $comment;                        }*/
					
					}
				
				}
			
			}
		
		}
		
		$real_comments = htmlspecialchars_deep_decode ( $real_comments );
		
		return $real_comments;
	
	}
	
	function commentsGetCount($to_table, $to_table_id, $is_moderated = false) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_comments'];
		
		$to_table_id = intval ( $to_table_id );
		
		if ($is_moderated != false) {
			
			$more_q = ' and is_moderated="y" ';
		
		}
		
		$q = "SELECT count(*) as qty from $table where to_table='$to_table' and to_table_id=$to_table_id  {$more_q}";
		
		$q = $this->core_model->dbQuery ( $q );
		
		return intval ( $q [0] ['qty'] );
		
	//  p ( $q ,1);	

	}
	
	function commentsGetForContentId($id, $is_moderated = false) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_comments'];
		
		$orderby [0] = 'created_on';
		
		$orderby [1] = 'DESC';
		
		$comments = array ();
		
		$comments ['to_table'] = 'table_content';
		
		$comments ['to_table_id'] = $id;
		
		if ($is_moderated != false) {
			
			$comments ['is_moderated'] = 'y';
		
		}
		
		$comments = $this->commentsGet ( $comments );
		
		return $comments;
	
	}
	
	function commentsGetCountForContentId($id, $is_moderated = false) {
		
		$c = $this->commentsGetForContentId ( $id, $is_moderated );
		
		if ($c == false) {
			
			return 0;
		
		}
		
		//var_Dump($c);		

		return intval ( count ( $c ) );
	
	}
	
	function commentsGetCounts($to_table, $only_ids = null) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_comments'];
		
		$master_table = $this->core_model->dbGetRealDbTableNameByAssocName ( $to_table );
		
		$where = "WHERE comments.is_moderated = 'n'";
		
		if ($only_ids !== null && is_array ( $only_ids )) {
			
			$where = "\n AND comments.to_table_id IN (" . implode ( ',', $only_ids ) . ")";
		
		}
		
		$query = "            select                COUNT(comments.id) AS comments_total,                master_table.id AS item_id            FROM                {$table} AS comments            INNER JOIN                {$master_table} AS master_table            ON                (comments.to_table = '{$to_table}' AND comments.to_table_id = master_table.id)            {$where}            GROUP BY                comments.to_table, comments.to_table_id        ";
		
		//      $this->core_model->cleanCacheGroup('comments');		

		$qty = $this->core_model->dbQuery ( $query, md5 ( $query ), 'comments' );
		
		return $qty;
	
	}
	
	function commentsGetNewCommentsCount() {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_comments'];
		
		$data ['is_moderated'] = 'n';
		
		$orderby [0] = 'updated_on';
		
		$orderby [1] = 'DESC';
		
		$get = $this->core_model->getDbData ( $table, $criteria = $data, $limit = false, $offset = false, $orderby = false, $cache_group = 'comments', $debug = false, $ids = false, $count_only = true );
		
		return $get;
	
	}
	
	function votesCast($to_table, $to_table_id) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_votes'];
		
		$the_table = $this->core_model->dbGetRealDbTableNameByAssocName ( $to_table );
		
		$check = $this->core_model->dbCheckIfIdExistsInTable ( $the_table, $to_table_id );
		
		if ($check == false) {
			
			//print 'id not exist?';			

			return FALSE;
		
		} else {
			
			$user_session = $this->session->userdata ( 'user_session' );
			
			$created_by = intval ( $user_session ['user_id'] );
			
			$created_by = $this->users_model->userId ();
			
			//p($created_by, 1);			

			if ($created_by > 0) {
				
				$check_if_user_voted_for_today = " SELECT count(*) as qty            from $table            where to_table='$to_table' and  to_table_id='$to_table_id'            and created_by=$created_by            ";
			
			} else {
				
				$ip = visitorIP ();
				
				$yesterday = date ( 'Y-m-d H:i:s', mktime ( 0, 0, 0, date ( "m" ), date ( "d" ) - 1, date ( "Y" ) ) );
				
				$check_if_user_voted_for_today = " SELECT count(*) as qty            from $table            where to_table='$to_table' and  to_table_id='$to_table_id'            and created_on > '$yesterday'            and user_ip = '$ip'            ";
			
			}
			
			//var_dump ( $check_if_user_voted_for_today );			

			$check_if_user_voted_for_today = $this->core_model->dbQuery ( $check_if_user_voted_for_today );
			
			$check_if_user_voted_for_today = intval ( $check_if_user_voted_for_today [0] ['qty'] );
			
			if ($check_if_user_voted_for_today == 0) {
				
				$cast_vote = array ();
				
				$cast_vote ['to_table'] = $to_table;
				
				$cast_vote ['to_table_id'] = $to_table_id;
				
				$cast_vote ['user_ip'] = $ip;
				
				$this->core_model->saveData ( $table, $cast_vote, $data_to_save_options = false );
				
				$this->core_model->cleanCacheGroup ( 'votes' );
				
				return true;
			
			} else {
				
				return false;
			
			}
		
		}
	
	}
	
	function votesGetCount($to_table, $to_table_id, $since_time = false) {
		
		if ($since_time == false) {
			
			$since_time = ' 1 year ';
		
		}
		
		if (($timestamp = strtotime ( $since_time )) === false) {
			
			return FALSE;
		
		}
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, 'votes' );
		
		if (($cache_content) != false) {
			
			if ($cache_content == 'false') {
				
				return 0;
			
			} else {
				
				return $cache_content;
			
			}
		
		}
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_votes'];
		
		$the_table = $this->core_model->dbGetRealDbTableNameByAssocName ( $to_table );
		
		$check = $this->core_model->dbCheckIfIdExistsInTable ( $the_table, $to_table_id );
		
		if ($check == false) {
			
			return FALSE;
		
		} else {
			
			//$yesterday = date ( 'Y-m-d H:i:s', mktime ( 0, 0, 0, date ( "m" ), date ( "d" ) - $since_days, date ( "Y" ) ) );			

			$voted = strtotime ( $since_time . ' ago' );
			
			//var_dump($voted);			

			//$when = strtotime ( 'now') - $voted;			

			//$when = strtotime ( 'now') - $when;			

			$yesterday = date ( 'Y-m-d H:i:s', $voted );
			
			$qty = " SELECT count(*) as qty            from $table            where to_table='$to_table' and  to_table_id='$to_table_id'            and created_on > '$yesterday'            ";
			
			//var_dump($qty);			

			$qty = $this->core_model->dbQuery ( $qty, $cache_id = md5 ( $qty ), $cache_group = 'votes' );
			
			$qty = $qty [0] ['qty'];
			
			$qty = intval ( $qty );
			
			if ($qty == 0) {
				
				$this->core_model->cacheWriteAndEncode ( 'false', $function_cache_id, 'votes' );
			
			} else {
				
				$this->core_model->cacheWriteAndEncode ( $qty, $function_cache_id, 'votes' );
			
			}
			
			return $qty;
		
		}
	
	}
	
	function votesGetCounts($to_table, $only_ids = null) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_votes'];
		
		$master_table = $this->core_model->dbGetRealDbTableNameByAssocName ( $to_table );
		
		$where = '';
		
		if ($only_ids !== null && is_array ( $only_ids )) {
			
			$where = "WHERE votes.to_table_id IN (" . implode ( ',', $only_ids ) . ")";
		
		}
		
		$query = "            select                COUNT(votes.id) AS votes_total,                master_table.id AS item_id            FROM                {$table} AS votes            INNER JOIN                {$master_table} AS master_table            ON                (votes.to_table = '{$to_table}' AND votes.to_table_id = master_table.id)            {$where}            GROUP BY                votes.to_table, votes.to_table_id        ";
		
		//      $this->core_model->cleanCacheGroup ( 'votes');		

		return $this->core_model->dbQuery ( $query, md5 ( $query ), 'votes' );
	
	}
	
	function content_pingServersWithNewContent() {
		
		if($_SERVER ["SERVER_NAME"] == 'localhost'){
		return false;	
		}
		
	if($_SERVER ["SERVER_NAME"] == '127.0.0.1'){
		return false;	
		}
		
		
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_content'];
		
		$q = " SELECT id, content_title from  $table where is_pinged='n' or is_pinged IS NULL limit 0,1 ";
		
		$q = $this->core_model->dbQuery ( $q );
		
		if (empty ( $q ) == true) {
			
			//print "Nothing to ping! \n\n  ";			

			return false;
		
		}
		
		$server = array (

		# See http://codex.wordpress.org/Update_Services for		

		# a more comprehensive list.		

		'Google' => 'http://blogsearch.google.com/ping/RPC2', 'Feed Burner' => 'http://ping.feedburner.com/', 'Moreover' => 'http://api.moreover.com/RPC2', 'Syndic8' => 'http://ping.syndic8.com/xmlrpc.php', 'BlogRolling' => 'http://rpc.blogrolling.com/pinger/', 'Technorati' => 'http://rpc.technorati.com/rpc/ping', 'Ping-o-Matic!' => 'http://rpc.pingomatic.com/' );
		
		$this->load->library ( 'xmlrpc' );
		
		foreach ( $server as $line_num => $line ) {
			
			$line = trim ( $line );
			
			if ($line != '') {
				
				if (empty ( $q ) == true) {
					
					//print "Nothing to ping2! \n\n  ";					

					return false;
				
				}
				
				//var_dump($q);				

				foreach ( $q as $the_post ) {
					
					$q2 = " UPDATE  $table set is_pinged='y' where id='{$the_post['id']}' ";
					
					$q2 = $this->core_model->dbQ ( $q2 );
					
					$url = $this->getContentURLByIdAndCache ( $the_post ['id'] );
					
					$pages = array ();
					
					$pages [] = $the_post ['content_title'];
					
					$pages [] = $url;
					
					$line = trim ( $line );
					
					//print "Pinging $line \n\r";					

					//$this->xmlrpc->server('http://rpc.pingomatic.com/', 80);					

					$this->xmlrpc->server ( $line, 80 );
					
					$this->xmlrpc->method ( 'weblogUpdates.ping' );
					
					//$this->xmlrpc->set_debug(TRUE);					

					//$this->xmlrpc->display_response ();					

					//print "With:  \n\r";					

					//print_r ( $pages );					

					$request = $pages;
					
					$this->xmlrpc->request ( $request );
					
					if (! $this->xmlrpc->send_request ()) {
						
					//echo $this->xmlrpc->display_error ();					

					}
					
				//print "\n Done pinging $line \n\r\n\r\n\r\n\r";				

				}
			
			}
		
		}
	
	}
	
	function mediaGetForContentId($content_id, $media_type = false) {
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		$content_id = intval ( $content_id );
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		$cache_group = 'content/' . $content_id;
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			if ($cache_content == 'false') {
				
				return false;
			
			} else {
				
				return $cache_content;
			
			}
		
		}
		
		//var_dump('content.id '. $content_id);		

		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_media'];
		
		$q = "SELECT id FROM $table WHERE to_table = 'table_content'        AND to_table_id = '$content_id'        AND media_type = '$media_type'        AND ID is not null ORDER BY media_order ASC";
		
		//($q);		

		$q = $this->core_model->dbQuery ( $q, __FUNCTION__ . md5 ( $q ), 'media' );
		
		$ids = $this->core_model->dbExtractIdsFromArray ( $q );
		
		if (! empty ( $ids )) {
			
			$media = $this->core_model->mediaGet2 ( false, false, $ids );
			
			$this->core_model->cacheWriteAndEncode ( $media, $function_cache_id, $cache_group );
			
			//$media = $this->core_model->mediaGet ( $to_table = 'table_content', $content_id, $media_type = false, $order = "ASC", $queue_id = false, $no_cache = false );			

			return $media;
			
		//p($media2);		

		//$this->template ['videos'] = $media2 ['videos'];		

		} else {
			
			$this->core_model->cacheWriteAndEncode ( 'false', $function_cache_id, $cache_group );
		
		}
	
	}
	
	function mediaGetIdsForContentId($content_id, $media_type = false) {
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		$content_id = intval ( $content_id );
		$cache_group = 'content/' . $content_id;
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			if ($cache_content == 'false') {
				
				return false;
			
			} else {
				
				return $cache_content;
			
			}
		
		}
		
		//var_dump('content.id '. $content_id);		

		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_media'];
		
		$q = "SELECT id FROM $table WHERE to_table = 'table_content'        AND to_table_id = '$content_id'        AND media_type = '$media_type'        AND ID is not null ORDER BY media_order ASC";
		
		//($q);		

		$q = $this->core_model->dbQuery ( $q, __FUNCTION__ . md5 ( $q ), $cache_group );
		
		$ids = $this->core_model->dbExtractIdsFromArray ( $q );
		
		if (! empty ( $ids )) {
			
			$this->core_model->cacheWriteAndEncode ( $ids, $function_cache_id, $cache_group );
			
			return $ids;
		
		} else {
			
			$this->core_model->cacheWriteAndEncode ( 'false', $function_cache_id, $cache_group );
		
		}
	
	}
	
	function report($to_table, $to_table_id) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_reports'];
		
		$the_table = $this->core_model->dbGetRealDbTableNameByAssocName ( $to_table );
		
		$check = $this->core_model->dbCheckIfIdExistsInTable ( $the_table, $to_table_id );
		
		if ($check == false) {
			
			//print 'id not exist?';			

			return FALSE;
		
		} else {
			
			$user_session = $this->session->userdata ( 'user_session' );
			
			$created_by = intval ( $user_session ['user_id'] );
			
			$created_by = $this->users_model->userId ();
			
			//p($created_by, 1);			

			if ($created_by > 0) {
				
				$check_if_user_voted_for_today = " SELECT count(*) as qty            from $table            where to_table='$to_table' and  to_table_id='$to_table_id'            and created_by=$created_by            ";
			
			} else {
				
				$ip = visitorIP ();
				
				$yesterday = date ( 'Y-m-d H:i:s', mktime ( 0, 0, 0, date ( "m" ), date ( "d" ) - 1, date ( "Y" ) ) );
				
				$check_if_user_voted_for_today = " SELECT count(*) as qty            from $table            where to_table='$to_table' and  to_table_id='$to_table_id'            and created_on > '$yesterday'            and user_ip = '$ip'            ";
			
			}
			
			//var_dump ( $check_if_user_voted_for_today );			

			$check_if_user_voted_for_today = $this->core_model->dbQuery ( $check_if_user_voted_for_today );
			
			$check_if_user_voted_for_today = intval ( $check_if_user_voted_for_today [0] ['qty'] );
			
			if ($check_if_user_voted_for_today == 0) {
				
				$cast_vote = array ();
				
				$cast_vote ['to_table'] = $to_table;
				
				$cast_vote ['to_table_id'] = $to_table_id;
				
				$cast_vote ['user_ip'] = $ip;
				
				$this->core_model->saveData ( $table, $cast_vote, $data_to_save_options = false );
				
				$this->core_model->cleanCacheGroup ( 'reports' );
				
				return true;
			
			} else {
				
				return false;
			
			}
		
		}
	
	}
	
	function reportsGetCount($to_table, $to_table_id, $since_time = false) {
		
		if ($since_time == false) {
			
			$since_time = ' 1 year ';
		
		}
		
		if (($timestamp = strtotime ( $since_time )) === false) {
			
			return FALSE;
		
		}
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_content = $this->core_model->cacheGetContentAndDecode ( $function_cache_id, 'reports' );
		
		if (($cache_content) != false) {
			
			if ($cache_content == 'false') {
				
				return 0;
			
			} else {
				
				return $cache_content;
			
			}
		
		}
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_reports'];
		
		$the_table = $this->core_model->dbGetRealDbTableNameByAssocName ( $to_table );
		
		$check = $this->core_model->dbCheckIfIdExistsInTable ( $the_table, $to_table_id );
		
		if ($check == false) {
			
			return FALSE;
		
		} else {
			
			//$yesterday = date ( 'Y-m-d H:i:s', mktime ( 0, 0, 0, date ( "m" ), date ( "d" ) - $since_days, date ( "Y" ) ) );			

			$voted = strtotime ( $since_time . ' ago' );
			
			//var_dump($voted);			

			//$when = strtotime ( 'now') - $voted;			

			//$when = strtotime ( 'now') - $when;			

			$yesterday = date ( 'Y-m-d H:i:s', $voted );
			
			$qty = " SELECT count(*) as qty            from $table            where to_table='$to_table' and  to_table_id='$to_table_id'            and created_on > '$yesterday'            ";
			
			//var_dump($qty);			

			$qty = $this->core_model->dbQuery ( $qty, $cache_id = md5 ( $qty ), $cache_group = 'reports' );
			
			$qty = $qty [0] ['qty'];
			
			$qty = intval ( $qty );
			
			if ($qty == 0) {
				
				$this->core_model->cacheWriteAndEncode ( 'false', $function_cache_id, 'reports' );
			
			} else {
				
				$this->core_model->cacheWriteAndEncode ( $qty, $function_cache_id, 'reports' );
			
			}
			
			return $qty;
		
		}
	
	}
	
	function reportsGet($params, $options = false) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_reports'];
		
		if (empty ( $options ['order'] )) {
			
			$orderby [0] = 'created_on';
			
			$orderby [1] = 'DESC';
			
			$options ['order'] = $orderby;
		
		} else {
			
			$options ['order'] = $options ['order'];
		
		}
		
		$options = array ();
		
		$options ['get_params_from_url'] = true;
		
		$options ['debug'] = false;
		
		$options ['items_per_page'] = 100;
		
		//$options ['group_by'] = 'to_table, to_table_id,from_user, type';		

		$options ['group_by'] = 'to_table, to_table_id';
		
		//$options ['order'] = $orderby;		

		$options ['cache'] = true;
		
		$options ['cache_group'] = 'reports';
		
		if (! empty ( $db_options )) {
			
			foreach ( $db_options as $k => $v ) {
				
				$options ["{$k}"] = $v;
			
			}
		
		}
		
		$data = $this->core_model->fetchDbData ( $table, $params, $options );
		
		return $data;
	
	}

}

?>
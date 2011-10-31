<?php
if (! defined ( 'BASEPATH' ))
	
	exit ( 'No direct script access allowed' );

/**

 * Microweber

 *

 * An open source CMS and application development framework for PHP 5.1 or newer

 *

 * @package     Microweber

 * @author      Peter Ivanov

 * @copyright   Copyright (c), Mass Media Group, LTD.

 * @license     http://ooyes.net

 * @link        http://ooyes.net

 * @since       Version 1.0



 */

// ------------------------------------------------------------------------


/** 

 * Content class

 *

 * @desc Functions for manipulation the main content of the cms, mainly in the content table from the DB

 * @access      public

 * @category    Content API

 * @subpackage      Core

 * @author      Peter Ivanov

 * @link        http://ooyes.net

 */

class content_model extends Model {
	
	function __construct() {
		
		parent::Model ();
		
		// ping google on random? must be set on cronjob
		$rand = rand ( 0, 100 );
		
		if ($rand < 5) {
			
			$this->content_pingServersWithNewContent ();
		
		}
	
	}
	
	/**

	 * @desc Function to save content into the content_table

	 * @param array

	 * @param boolean

	 * @return string | the id saved

	 * @author      Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function saveContent($data, $delete_the_cache = true) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_content'];
		
		if (empty ( $data )) {
			
			return false;
		
		}
		
		$data_to_save = $data;
		
		$more_categories_to_delete = array ();
		if (intval ( $data ['id'] ) != 0) {
			
			$q = "SELECT * from $table where id='{$data_to_save['id']}' ";
			
			$q = CI::model ( 'core' )->dbQuery ( $q );
			
			$thecontent_title = $q [0] ['content_title'];
			
			$q = $q [0] ['content_url'];
			
			$thecontent_url = $q;
			
			$more_categories_to_delete = CI::model ( 'taxonomy' )->getTaxonomiesForContent ( $data ['id'], 'categories' );
		
		} else {
			
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
				

				$data ['content_url'] = CI::model ( 'core' )->url_title ( $thecontent_title );
			
			}
		
		} else {
			if ($thecontent_title != false) {
				$data ['content_url'] = CI::model ( 'core' )->url_title ( $thecontent_title );
			}
		}
		
		if ($data ['content_url'] != false) {
			$data ['content_url'] = CI::model ( 'core' )->url_title ( $data ['content_url'] );
			
			if (strval ( $data ['content_url'] ) == '') {
				
				$data ['content_url'] = CI::model ( 'core' )->url_title ( $data ['content_title'] );
			
			}
			
			$date123 = date ( "YmdHis" );
			
			$q = "select id, content_url from $table where content_url LIKE '{$data ['content_url']}'";
			
			$q = CI::model ( 'core' )->dbQuery ( $q );
			
			if (! empty ( $q )) {
				
				$q = $q [0];
				
				if ($data ['id'] != $q ['id']) {
					
					$data ['content_url'] = $data ['content_url'] . '-' . $date123;
				
				}
			
			}
			
			if (strval ( $data_to_save ['content_url'] ) == '' and ($data_to_save ['quick_save'] == false)) {
				
				$data_to_save ['content_url'] = $data_to_save ['content_url'] . '-' . $date123;
			
			}
			
			if (strval ( $data_to_save ['content_title'] ) == '' and ($data_to_save ['quick_save'] == false)) {
				
				$data_to_save ['content_title'] = 'post-' . $date123;
			
			}
			if (strval ( $data_to_save ['content_url'] ) == '' and ($data_to_save ['quick_save'] == false)) {
				$data_to_save ['content_url'] = strtolower ( reduce_double_slashes ( $data ['content_url'] ) );
			}
			//		$data_to_save ['content_url_md5'] = md5 ( $data_to_save ['content_url'] );
		

		}
		
		$data_to_save_options = array ();
		
		if ($delete_the_cache == true) {
			//	CI::model('core')->cleanCacheGroup ( 'content' );
		//	CI::model('core')->cleanCacheGroup ( 'media' );
		//	CI::model('core')->cleanCacheGroup ( 'global' );
		}
		
		if ($data_to_save ['is_home'] == 'y') {
			$sql = "UPDATE $table set is_home='n'   ";
			$q = CI::db ()->query ( $sql );
		}
		
		//parse images
		

		if ($data ['content_body'] != false) {
			$data_to_save ['content_body'] = $this->parseContentBodyItems ( $data_to_save ['content_body'], $data_to_save ['content_title'] );
		}
		if ($data_to_save ['content_filename_sync_with_editor'] == 'y') {
		
		}
		
		foreach ( $data_to_save as $k => $v ) {
			
			if (is_array ( $v ) == false) {
				
				$data_to_save [$k] = $this->parseContentBodyItems ( $v, $data_to_save ['content_title'] );
			
			}
		
		}
		
		if ($data_to_save ['content_body_filename'] != false) {
			
			if (trim ( $data_to_save ['content_body_filename'] ) != '') {
				
				$the_active_site_template = CI::model ( 'core' )->optionsGetByKey ( 'curent_template' );
				
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
		if (strval ( $data_to_save ['content_subtype_value'] ) == '') {
			$adm = is_admin ();
			
			if ($data_to_save ['content_subtype_value_new'] != '') {
				
				if ($adm == true) {
					
					$new_category = array ();
					$new_category ["taxonomy_type"] = "category";
					$new_category ["taxonomy_value"] = $data_to_save ['content_subtype_value_new'];
					$new_category ["parent_id"] = "0";
					
					$new_category = CI::model ( 'taxonomy' )->taxonomySave ( $new_category );
					
					$data_to_save ['content_subtype_value'] = $new_category;
					$data_to_save ['content_subtype'] = 'blog_section';
				
				}
			}
			if ($data_to_save ['content_subtype_value_auto_create'] == '') {
				$data_to_save ['content_subtype_value_auto_create'] = $data_to_save ['auto_create_categories'];
			}
			if ($data_to_save ['content_subtype_value_auto_create'] != '') {
				
				if ($adm == true) {
					
					$scats = explode ( ',', $data_to_save ['content_subtype_value_auto_create'] );
					if (! empty ( $scats )) {
						foreach ( $scats as $sc ) {
							$new_scategory = array ();
							$new_scategory ["taxonomy_type"] = "category";
							$new_scategory ["taxonomy_value"] = $sc;
							$new_scategory ["parent_id"] = intval ( $new_category );
							
							$new_scategory = CI::model ( 'taxonomy' )->taxonomySave ( $new_scategory );
						
						}
					}
				
				}
			}
		
		}
		//p ( $data_to_save, 1 );
		$save = CI::model ( 'core' )->saveData ( $table, $data_to_save );
		$id = $save;
		
		//if ($data_to_save ['content_type'] == 'page') {
		

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
				
				CI::model ( 'core' )->cleanCacheGroup ( 'menus' );
			
			}
		
		}
		
		//	}
		

		//CI::model('core')->cacheDeleteAll (); 
		

		if ($data_to_save ['preserve_cache'] == false) {
			if (intval ( $data_to_save ['content_parent'] ) != 0) {
				CI::model ( 'core' )->cleanCacheGroup ( 'content' . DIRECTORY_SEPARATOR . intval ( $data_to_save ['content_parent'] ) );
			}
			CI::model ( 'core' )->cleanCacheGroup ( 'content' . DIRECTORY_SEPARATOR . $id );
			CI::model ( 'core' )->cleanCacheGroup ( 'content' . DIRECTORY_SEPARATOR . '0' );
			CI::model ( 'core' )->cleanCacheGroup ( 'content' . DIRECTORY_SEPARATOR . 'global' );
			
			if (! empty ( $data_to_save ['taxonomy_categories'] )) {
				foreach ( $data_to_save ['taxonomy_categories'] as $cat ) {
					
					CI::model ( 'core' )->cleanCacheGroup ( 'taxonomy' . DIRECTORY_SEPARATOR . intval ( $cat ) );
				}
				CI::model ( 'core' )->cleanCacheGroup ( 'taxonomy' . DIRECTORY_SEPARATOR . '0' );
				CI::model ( 'core' )->cleanCacheGroup ( 'taxonomy' . DIRECTORY_SEPARATOR . 'global' );
			
			}
			
			if (! empty ( $more_categories_to_delete )) {
				foreach ( $more_categories_to_delete as $cat ) {
					CI::model ( 'core' )->cleanCacheGroup ( 'taxonomy' . DIRECTORY_SEPARATOR . intval ( $cat ) );
				}
			}
		}
		return $save;
	
	}
	
	function parseContentBodyItems($original_content, $title) {
		
		$original_content = clean_word ( $original_content );
		
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
		
		$possible_filename = CI::model ( 'core' )->url_title ( $title, 'dash', true );
		
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
		

		$media_url = MEDIA_URL;
		
		if (! empty ( $images )) {
			
			foreach ( $images as $image ) {
				
				if ((stristr ( $image, '.jpg' ) == true) or (stristr ( $image, '.png' ) == true) or (stristr ( $image, '.gif' ) == true) or (stristr ( $image, '.bmp' ) == true) or (stristr ( $image, '.jpeg' ) == true)) 

				{
					
					$orig_image = $image;
					
					if (stristr ( $image, '{MEDIAURL}' == false )) {
						
						if (stristr ( $image, $media_url ) == true) {
						
						} else {
							
							if (CI::model ( 'core' )->url_IsFile ( $image ) == true) {
								
								$to_get = $image;
							
							} else {
								
								$image = 'http://maksoft.net/' . $image;
								
								if (CI::model ( 'core' )->url_IsFile ( $image ) == true) {
									
									$to_get = $image;
								
								}
							
							}
							
							if (CI::model ( 'core' )->url_IsFile ( $image ) == true) {
								
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
									

									/*  var_dump ( $image );

                                print "<hr>";

                                var_dump ( $dir . $currentFile );

                                print "<hr>";

                                print "<hr>";*/
									
									//CI::model('core')->url_getPageToFile ( $image, $dir . $currentFile );
									

									CurlTool::downloadFile ( $image, $dir . $currentFile, false );
									
									$the_new_image = '{MEDIAURL}' . 'downloaded/' . $currentFile;
									
									//  $content_item = str_ireplace ( $image, $the_new_image, $content_item );
									

									$content_item = str_ireplace ( $orig_image, $the_new_image, $content_item );
									
									$content_item = str_ireplace ( $media_url, '{MEDIAURL}', $content_item );
								
								}
							
							} else {
								
							//print 'no file: ' . $image;
							

							}
						
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
		
		$the_post = get_post ( $post_id );
		
		if ($the_post ['content_type'] == 'page') {
			
			$content_parent_new = intval ( $the_post ['content_parent'] );
			
			$q = " UPDATE $table set content_parent='{$content_parent_new}' where content_parent='{$id}'  ";
			
			$q = CI::model ( 'core' )->dbQ ( $q );
		}
		
		$data = array ();
		
		$data ['id'] = $id;
		
		$del = CI::model ( 'core' )->deleteData ( $table, $data, 'content' );
		
		$data = array ();
		
		$data ['to_table'] = 'table_content';
		
		$data ['to_table_id'] = $id;
		
		$del = CI::model ( 'core' )->deleteData ( $table_taxonomy, $data );
		
		$table_comments = $cms_db_tables ['table_comments'];
		
		$data = array ();
		
		$data ['to_table'] = 'table_content';
		
		$data ['to_table_id'] = $id;
		
		$del = CI::model ( 'core' )->deleteData ( $table_comments, $data );
		
		$table_comments = $cms_db_tables ['table_taxonomy'];
		
		$data = array ();
		
		$data ['to_table'] = 'table_content';
		
		$data ['to_table_id'] = $id;
		
		$del = CI::model ( 'core' )->deleteData ( $table_comments, $data );
		
		$table_v = $cms_db_tables ['table_votes'];
		
		$data = array ();
		
		$data ['to_table'] = 'table_content';
		
		$data ['to_table_id'] = $id;
		
		$del = CI::model ( 'core' )->deleteData ( $table_v, $data );
		
		$table = $cms_db_tables ['table_reports'];
		
		$data = array ();
		
		$data ['to_table'] = 'table_content';
		
		$data ['to_table_id'] = $id;
		
		$del = CI::model ( 'core' )->deleteData ( $table, $data );
		
		$table = $cms_db_tables ['table_custom_fields'];
		
		$data = array ();
		
		$data ['to_table'] = 'table_content';
		
		$data ['to_table_id'] = $id;
		
		$del = CI::model ( 'core' )->deleteData ( $table, $data );
		
		$table = $cms_db_tables ['table_users_notifications'];
		
		$data = array ();
		
		$data ['to_table'] = 'table_content';
		
		$data ['to_table_id'] = $id;
		
		$del = CI::model ( 'core' )->deleteData ( $table, $data );
		
		CI::model ( 'core' )->cleanCacheGroup ( 'content' . DIRECTORY_SEPARATOR . $id );
		CI::model ( 'core' )->cleanCacheGroup ( 'content' . DIRECTORY_SEPARATOR . '0' );
		CI::model ( 'core' )->cleanCacheGroup ( 'content' . DIRECTORY_SEPARATOR . 'global' );
		
	//$table = $table_menus;
	

	//$data = array ( );
	

	//$data ['content_id'] = $id;
	

	//$del = CI::model('core')->deleteData ( $table, $data, 'menus' );
	

	//$this->fixMenusPositions ();
	

	//$data = array ( );
	

	//$data ['to_table'] = 'table_content';
	

	//$data ['to_table_id'] = $id;
	

	//$del = CI::model('core')->deleteData ( $table_taxonomy, $data, 'taxonomy' );
	

	//CI::model('core')->cleanCacheGroup ( 'content' );
	

	//  CI::model('core')->cleanCacheGroup ( 'core' );
	

	//CI::model('core')->cleanCacheGroup ( 'global' );
	

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
		
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		$content = $this->getPostByURL ( $content_id, $url, $no_recursive = false );
		
		CI::model ( 'core' )->cacheWriteAndEncode ( $content, $function_cache_id, $cache_group );
		
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
		
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
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
		
		//$content_url_md5 = md5 ( $url );
		

		$sql = "SELECT id, content_url from $table where content_url='$url' and content_type='post'   order by updated_on desc limit 0,1 ";
		
		$q = CI::model ( 'core' )->dbQuery ( $sql, md5 ( $sql ), 'content/global' );
		
		$result = $q;
		
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
			
			CI::model ( 'core' )->cacheWriteAndEncode ( $content, $function_cache_id, $cache_group );
			
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
		
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		} else {
			
			$to_cache = $this->getContentByURL ( $url, $no_recursive = $no_recursive );
			
			CI::model ( 'core' )->cacheWriteAndEncode ( $to_cache, $function_cache_id, $cache_group );
			
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
		
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
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
			
			CI::model ( 'core' )->cacheWriteAndEncode ( $to_cache, $function_cache_id, $cache_group );
			
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
		
		$table = $cms_db_tables ['table_content'];
		
		$url = strtolower ( $url );
		
		$url = addslashes ( $url );
		
		$sql = "SELECT id,content_url from $table where content_url='$url' and is_active='y'   and content_type='page' order by updated_on desc limit 0,1 ";
		
		//print $sql;
		

		//exit ();
		

		$q = CI::model ( 'core' )->dbQuery ( $sql, md5 ( $sql ), 'content/global' );
		
		$result = $q;
		
		$content = $result [0];
		
		if ($try_to_return_post_by_url == true) {
			
			if (empty ( $content )) {
				
				$sql = "SELECT id,content_url from $table where content_url='$url' and is_active='y'   and content_type='post' order by updated_on desc limit 0,1 ";
				
				$q = CI::model ( 'core' )->dbQuery ( $sql, md5 ( $sql ), 'content/global' );
				
				$result = $q;
				
				$content = $result [0];
			
			}
			
			if (! empty ( $content )) {
				
				$content = $this->contentGetByIdAndCache ( $content ['id'] );
			
			}
		
		}
		
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
		
		$table = $cms_db_tables ['table_content'];
		
		$url = strtolower ( $url );
		$url = codeClean ( $url );
		$url = addslashes ( $url );
		$sql = "SELECT id,content_url from $table where content_url='{$url}' or content_title LIKE '{$url}'   order by updated_on desc limit 0,1 ";
		
		$q = CI::model ( 'core' )->dbQuery ( $sql, md5 ( $sql ), 'content/global' );
		
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
		
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id, $cache_group = 'content/' . $id );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		$the_url = $this->getContentURLById ( $id );
		
		$cache = CI::model ( 'core' )->cacheWriteAndEncode ( $the_url, $function_cache_id, $cache_group = 'content/' . $id );
		
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
		
		$sql = "SELECT * from $table where is_home='y'  order by updated_on desc limit 0,1 ";
		
		$q = CI::model ( 'core' )->dbQuery ( $sql, 'getContentHomepage' . md5 ( $sql ), 'content/global' );
		//var_dump($q);
		$result = $q;
		
		$content = $result [0];
		
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
		
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id, $cache_content );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		$data = $this->getParentPagesIdsForPageId ( $id );
		
		$cache = CI::model ( 'core' )->cacheWriteAndEncode ( $data, $function_cache_id, $cache_group );
		
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
		
		//$taxonomies = CI::model('core')->dbQuery ( $q, $cache_id = md5 ( $q ), $cache_group = 'content' );
		

		$taxonomies = CI::model ( 'core' )->dbQuery ( $q, $cache_id = md5 ( $q ), $cache_group = 'content/' . $id );
		
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
			
			$ids = array_unique ( $ids );
			
			return $ids;
		
		} else {
			
			return false;
		
		}
	
	}
	
	function contentGetHrefForPostId($id) {
		if (intval ( $id ) == 0) {
			return false;
		}
		global $cms_db_tables;
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		$cache_group = 'content/' . $id;
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		$content = $this->contentGetByIdAndCache ( $id );
		//p($content);
		

		$cats = CI::model ( 'taxonomy' )->getCategoriesForContent ( $id );
		//p ( $cats );
		//	
		

		if (! empty ( $cats )) {
			
			$url = false;
			
			$master = CI::model ( 'taxonomy' )->getMasterCategories ();
			
			foreach ( $cats as $c ) {
				
				if ($url == false) {
					
					foreach ( $master as $m ) {
						
						if ($m ['id'] == $c ['id']) {
							
							$url = CI::model ( 'taxonomy' )->getUrlForIdAndCache ( $m ['id'] ); // . '/' . $content ['content_url'];
							

							$url1 = $url . '/' . $content ['content_url'];
							//p($url1);
						

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
			
			}
		
		}
		
		//var_dump($cats);
		

		/*$cache = base64_encode ( $the_url );

        $table_cache = $cms_db_tables ['table_cache'];

        $q = "delete from $table_cache where cache_id='$cache_id'  ";

        CI::model('core')->dbQ ( $q );

        CI::model('core')->cacheDeleteFile ( $cache_id );

        CI::model('core')->cacheWriteContent ( $cache_id, $cache );

        $q = "INSERT INTO $table_cache set cache_id='$cache_id', cache_group='content' ";

        CI::model('core')->dbQ ( $q );

        */
		
		//cache
		

		$cache = CI::model ( 'core' )->cacheWriteAndEncode ( $the_url, $function_cache_id, $cache_group );
		
		return $the_url;
	
	}
	
	function contentsCheckIfContentExistsById($id) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_content'];
		
		$q = " SELECT count(*) as qty from $table where id=$id";
		
		$q = CI::model ( 'core' )->dbQuery ( $q );
		
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
			
			$taxonomy = CI::model ( 'taxonomy' )->getParents ( $category_id );
			
			//var_dump ( $taxonomy );
			foreach ( $taxonomy as $item ) {
				
				$data1 = array ();
				
				$data1 ['content_type'] = 'page';
				
				$data1 ['content_subtype'] = 'blog_section';
				
				$data1 ['content_subtype_value'] = $item;
				
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
			
			$taxonomy = CI::model ( 'taxonomy' )->getChildrensRecursiveAndCache ( $category_id, $type = 'category' );
			
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
			
			$taxonomy = CI::model ( 'taxonomy' )->getParents ( $category_id );
			
			//var_dump ( $taxonomy );
			

			if (! empty ( $taxonomy )) {
				
				foreach ( $taxonomy as $item ) {
					
					$data1 = array ();
					
					$data1 ['content_type'] = 'page';
					
					$data1 ['content_subtype'] = 'blog_section';
					
					$data1 ['content_subtype_value'] = $item;
					
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
		
		$pics = CI::model ( 'core' )->mediaGetImages ( 'table_content', $to_table_id = $id, $size = $size, $order_direction = $order_direction );
		
		return $pics;
	
	}
	
	function contentGetThumbnailForContentId($id, $size = 128, $size_h = false) {
		if (intval ( $id ) == 0) {
			return false;
		}
		global $cms_db_tables;
		//echo ' line:'. __LINE__ .' file:'. __FILE__ .' directory:'. __DIR__ .' function:'. __FUNCTION__ .' class:'. __CLASS__ .' method:'. __METHOD__ .' namespace:'. __NAMESPACE__;
		

		$cache_group = 'media/table_content/' . $id;
		$function_cache_id = serialize ( $id ) . serialize ( $size ) . serialize ( $size_h );
		
		$function_cache_id = __FUNCTION__ . md5 ( __FUNCTION__ . $function_cache_id );
		
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		$data = false;
		
		$data ['id'] = $id;
		
		$images = $this->mediaGetForContentId ( $id, $media_type = 'picture' );
		
		if (! empty ( $images )) {
			
			$src = CI::model ( 'core' )->mediaGetThumbnailForMediaId ( $images ['pictures'] [0] ['id'], $size );
			
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
		
		$input = (html_entity_decode ( $content ['content_body'] ));
		
		$input = $this->applyGlobalTemplateReplaceables ( $input );
		
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
		
		$media_url = MEDIA_URL;
		
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
					
					$download = CI::model ( 'core' )->url_getPageToFile ( $images [0], $newfilename_path );
					
					if ($download == true) {
						
						$image_new = $media_url . $newfilename;
						
						$update_body_src = str_ireplace ( site_url (), '{SITE_URL}', $image_new );
						
						//  $update_body_src_escaped =  $this->db->escape ( $update_body_src );
						

						$new_body = str_replace ( $images [0], $update_body_src, $content ['content_body'] );
						
						//p($new_body,1);
						

						$q = "UPDATE $table_content set content_body= '$new_body' where id ='{$content ['id']}'  ";
						
						$q = CI::model ( 'core' )->dbQ ( $q );
						
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
			
			CI::model ( 'core' )->cacheWriteAndEncode ( $src, $function_cache_id, $cache_group );
			
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
		
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_content'];
		
		$content = $this->contentGetByIdAndCache ( $content_id );
		
		$q = " select count(*) as qty from $table  where content_parent= '{$content['content_parent']}'  and updated_on < '{$content['updated_on']}' order by updated_on DESC   ";
		
		$q = CI::model ( 'core' )->dbQuery ( $q );
		
		$q = intval ( $q [0] ['qty'] );
		
		$q = $q + 1;
		
		CI::model ( 'core' )->cacheWriteAndEncode ( $q, $function_cache_id, $cache_group );
		
		return $q;
	
	}
	
	/**

	 * @desc  Cache function for contentGetByIdAndCache

	 * @param int

	 * @return array

	 * @author      Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function contentGetByIdAndCache($id) {
		$id = intval ( $id );
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_group = 'content/' . $id;
		
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		//  $cache_content = false;
		

		if (($cache_content) != false) {
			
			return $cache_content;
		
		} else {
			
			$content = $this->contentGetById ( $id );
			
			$to_cache = $content;
			
			CI::model ( 'core' )->cacheWriteAndEncode ( $to_cache, $function_cache_id, $cache_group );
			
			return $to_cache;
		
		}
	
	}
	
	/**

	 * @desc Function to get single content item by id from the content_table

	 * @param int

	 * @return array

	 * @author      Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function contentGetById($id) {
		global $cms_db_tables;
		$table = $cms_db_tables ['table_content'];
		$id = intval ( $id );
		$q = "SELECT * from $table where id='$id'  limit 0,1 ";
		
		$q = CI::model ( 'core' )->dbQuery ( $q, __FUNCTION__ . md5 ( $q ), 'content/' . $id );
		$content = $q [0];
		
		if (! empty ( $content )) {
			$media_url = MEDIA_URL;
			
			if ($content ['content_body'] != '') {
				$desc = htmlspecialchars_decode ( $content ['content_body'], ENT_QUOTES );
				
				$desc = str_ireplace ( '{MEDIAURL}', $media_url, $desc );
				
				$desc = str_ireplace ( '{SITEURL}', site_url (), $desc );
				//print $desc;
				$content ['the_content_body'] = $desc;
			}
		
		}
		
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
		
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		} else {
			
			$to_cache = $this->contentGetActiveCategoriesForPostId ( $content_id, $starting_from_category_id );
			
			CI::model ( 'core' )->cacheWriteAndEncode ( $to_cache, $function_cache_id, $cache_group );
			
			return $to_cache;
		
		}
	
	}
	
	/**

	 * @desc Generate unique content URL for post by specifying the id and the titile

	 * @param the_id

	 * @param the_content_title

	 * @return string

	 * @author      Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function contentGenerateUniqueUrlTitleFromContentTitle($the_id, $the_content_title, $the_content_url_overide = false) {
		
		global $cms_db_tables;
		
		CI::helper ( 'url' );
		
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
			
			$q = CI::model ( 'core' )->dbQuery ( $q );
			
			$thecontent_url = $q [0] ['content_url'];
			
			$the_new_content_url = CI::model ( 'core' )->url_title ( $the_content_title, 'dash', true );
			
			$the_id = intval ( $the_id );
			
			$q = "SELECT content_url, content_title from $table where content_url='$the_new_content_url' and id!=$the_id limit 0,1 ";
			
			$q = CI::model ( 'core' )->dbQuery ( $q );
			
			if (! empty ( $q )) {
				
				$the_new_content_url = $the_new_content_url . date ( "ymdhis" );
			
			}
		
		} else {
			
			$the_new_content_url = CI::model ( 'core' )->url_title ( $the_content_title, 'dash', true );
			
			$q = "SELECT content_url, content_title from $table where content_url='$the_new_content_url' limit 0,1 ";
			
			$q = CI::model ( 'core' )->dbQuery ( $q );
			
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
		
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
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
		

		$cats = CI::model ( 'taxonomy' )->taxonomyGet ( $taxonomy, $orderby = false, $no_limits = true );
		
		//taxonomyGet($data = false, $orderby = false, $no_limits = false)
		

		//  var_dump($cats);
		

		if (empty ( $cats )) {
			
			return false;
		
		} else {
			
			$return_cats = array ();
			
			if ($starting_from_category_id != false) {
				
				$available_cats = CI::model ( 'taxonomy' )->getChildrensRecursiveAndCache ( $starting_from_category_id, $type = 'category' );
				
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
			
			CI::model ( 'core' )->cacheWriteAndEncode ( $return_cats, $function_cache_id, $cache_group );
			
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
		
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		} else {
			
			$to_cache = $this->contentActiveCategoriesForPageId2 ( $page_id, $url, $no_base );
			
			CI::model ( 'core' )->cacheWriteAndEncode ( $to_cache, $function_cache_id, $cache_group );
			
			return $to_cache;
		
		}
	
	}
	
	function contentActiveCategoriesForPageId2($page_id, $url, $no_base = false) {
		
		/*$args = func_get_args ();

        foreach ( $args as $k => $v ) {

            $function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );

        }

        $function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );

        $cache_content = CI::model('core')->cacheGetContentAndDecode ( $function_cache_id );

        if (($cache_content) != false) {

            if ($cache_content == 'false') {

                return false;

            } else {

                return $cache_content;

            }

        }*/
		
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
								
								//$possible_ids = CI::model('taxonomy')->taxonomyGetTaxonomyIdsForTaxonomyRootIdAndCache ( $base_category, true, false, 'category' );
								

								$possible_ids = array ($base_category );
								$possible_ids_more = CI::model ( 'taxonomy' )->getChildrensRecursive ( $base_category, 'category' );
								if (! empty ( $possible_ids_more )) {
									$possible_ids = array_merge ( $possible_ids, $possible_ids_more );
								
								}
								
								//var_dump ( $possible_ids );
								

								//exit ();
								

								$data = array ();
								
								$data ['taxonomy_value'] = $taxonomy_value;
								
								$data ['taxonomy_value2'] = rawurldecode ( $taxonomy_value );
								
								$data ['taxonomy_type'] = 'category';
								
								$some_integer = intval ( $data ['taxonomy_value'] );
								
								$q = "Select id from $table_taxonomy where

                                (taxonomy_value='{$data ['taxonomy_value']}' or

                                taxonomy_value='{$data ['taxonomy_value2']}' or id=$some_integer



                                )

                                and taxonomy_type='{$data ['taxonomy_type']}'

                                ";
								
								$results = CI::model ( 'core' )->dbQuery ( $q, __FUNCTION__ . md5 ( $q ), 'taxonomy/global' );
								
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
							

							$categories_get_from_url = CI::model ( 'core' )->getParamFromURL ( 'categories' );
							
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
										
										/*$data ['taxonomy_value'] = $taxonomy_value;

                                        $data ['taxonomy_type'] = 'category';

                                        //  $data ['to_table'] = 'tab';

                                        //var_dump($possible_ids);

                                        $results = CI::model('taxonomy')->taxonomyGet ( $data );*/
										
										$some_integer = intval ( $taxonomy_value );
										
										$q = "Select id from $table_taxonomy where

                                (taxonomy_value='{$taxonomy_value}' or

                                taxonomy_value='{$taxonomy_value}' or id=$some_integer



                                )

                                and taxonomy_type='category'

                                ";
										
										// p($q);
										

										$results = CI::model ( 'core' )->dbQuery ( $q, __FUNCTION__ . md5 ( $q ), 'taxonomy/global' );
										
										//var_dump ( $results );
										

										if (! empty ( $results )) {
											
											foreach ( $results as $res ) {
												
												//  if (in_array ( $res ["id"], $possible_ids ) == true) {
												

												if (intval ( $res ["id"] ) != 0) {
													
													$categories_to_return [] = $res ["id"];
													
												//  $possible_ids1 = CI::model('taxonomy')->taxonomyGetTaxonomyIdsForTaxonomyRootIdAndCache (  $res ["id"], true );
												

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
			
		/*if (! empty ( $categories_to_return )) {

                CI::model('core')->cacheWriteAndEncode ( $categories_to_return, $function_cache_id );

                

                return $categories_to_return;

            } else {

                CI::model('core')->cacheWriteAndEncode ( 'false', $function_cache_id );

                

                return false;

            }*/
		
		} else {
			
			//CI::model('core')->cacheWriteAndEncode ( 'false', $function_cache_id );
			

			return false;
		
		}
	
	}
	
	function contentGetVotesCountForContentId($id, $since_days) {
		
	//$qty = $this->votesGetCount ( 'table_content', $id, $since_days = $since_days );
	

	//return $qty;
	

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
								
								//$possible_ids = CI::model('taxonomy')->taxonomyGetTaxonomyIdsForTaxonomyRootIdAndCache ( $base_category, true, false, 'category' );
								

								$possible_ids = array ($base_category );
								$possible_ids_more = CI::model ( 'taxonomy' )->getChildrensRecursive ( $base_category, 'category' );
								if (! empty ( $possible_ids_more )) {
									$possible_ids = array_merge ( $possible_ids, $possible_ids_more );
								
								}
								
								$data = array ();
								
								$data ['taxonomy_value'] = $taxonomy_value;
								
								//var_dump($possible_ids);
								

								$results = CI::model ( 'taxonomy' )->taxonomyGetAndCache ( $data );
								
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
	
	/**

	 * Gets content by params

	 * if no params are used it will try to get them from the URL

	 *

	 *

	 *

	 *

	 *

	 *

	 *

	 *

	 */
	
	function contentGetByParams($params) {
		
		extract ( $params );
		
		$posts_data = false;
		
		if ($selected_categories == false) {
			if ($categories) {
				$selected_categories = $categories;
			}
		}
		
		$posts_data ['selected_categories'] = $selected_categories;
		$posts_data ['orderby'] = $order;
		if ($voted_by) {
			$posts_data ['voted_by'] = $voted_by;
		}
		
		$orderby = $order;
		
		//p($selected_categories);
		

		if (empty ( $posts_data ['selected_categories'] )) {
			
			$categoris_from_url = CI::model ( 'core' )->getParamFromURL ( 'selected_categories' );
			
			if ($categoris_from_url != '') {
				
				$categoris_from_url = array_trim ( explode ( ',', $categoris_from_url ) );
				$posts_data ['selected_categories'] = $categoris_from_url;
			
			}
		
		}
		
		if ($keyword) {
			$search_by_keyword = trim ( $keyword );
		}
		
		if (! $search_by_keyword) {
			if ($_POST ['search_by_keyword'] != '') {
				
				$search_for = $_POST ['search_by_keyword'];
				
				$params ['search_for'] = $_POST ['search_by_keyword'];
			
			}
		} else {
			$params ['search_for'] = $search_by_keyword;
		}
		
		if ($id != false) {
			
			$params ['id'] = intval ( $id );
		
		} else {
		
		}
		
		if ($count != false) {
			
			$params ['count'] = true;
		
		}
		
		if ($params ['strict_category_selection'] == false) {
			
			$strict_category_selection = CI::model ( 'core' )->getParamFromURL ( 'strict_category_selection' );
		
		} else {
		
		}
		
		if ($custom_fields_criteria == false) {
			
			$cf = CI::model ( 'core' )->getParamFromURL ( 'custom_fields_criteria' );
			//p($cf,1);
			if ($cf != false) {
				
				$posts_data ['custom_fields_criteria'] = $cf;
			
			}
		
		} else {
			
			$posts_data ['custom_fields_criteria'] = $custom_fields_criteria;
		
		}
		
		if ($params ['search_for'] == false) {
			
			$search_for = CI::model ( 'core' )->getParamFromURL ( 'keyword' );
		
		} else {
			$search_for = $params ['search_for'];
		}
		
		if ($search_for != '') {
			
			$search_for = html_entity_decode ( $search_for );
			
			$search_for = urldecode ( $search_for );
			
			$search_for = htmlspecialchars_decode ( $search_for );
			
			$posts_data ['search_by_keyword'] = $search_for;
		
		}
		
		if ($params ['is_special'] == false) {
			
			$is_special = CI::model ( 'core' )->getParamFromURL ( 'is_special' );
			
			if (($is_special == 'y') or ($is_special == 'n')) {
				
				$posts_data ['is_special'] = $is_special;
			
			}
		
		} else {
			
			$posts_data ['is_special'] = $params ['is_special'];
		
		}
		
		if ($content_subtype == false) {
			
			if ($type == false) {
				
				$type = CI::model ( 'core' )->getParamFromURL ( 'type' );
			
			}
			
			if (trim ( $type ) != '' && trim ( $type ) != 'blog') {
				
				$posts_data ['content_subtype'] = $type;
			
			} else {
				
			//	$posts_data ['content_subtype'] = 'none';
			

			}
			
			if (trim ( $type ) == 'all') {
				
				unset ( $posts_data ['content_subtype'] );
			
			}
		
		} else {
			
			$posts_data ['content_subtype'] = $content_subtype;
		
		}
		
		if ($typev == false) {
			
			$typev = CI::model ( 'core' )->getParamFromURL ( 'typev' );
		
		}
		
		if (trim ( $typev ) != '') {
			
			$posts_data ['content_subtype_value'] = $typev;
		
		} else {
			
		//	$posts_data ['content_subtype_value'] = 'none';
		

		}
		
		if ($content_type == false) {
			
			$content_type = 'post';
		
		}
		
		$posts_data ['content_type'] = $content_type;
		
		if ($items_per_page == false) {
			$items_per_page = $limit;
		
		}
		
		if ($items_per_page == false) {
			
			$items_per_page = CI::model ( 'core' )->optionsGetByKey ( 'default_items_per_page' );
		
		}
		
		$items_per_page = intval ( $items_per_page );
		
		if (empty ( $active_categories2 )) {
			
			$active_categories2 = array ();
		
		}
		
		if ($curent_page == false) {
			
			$curent_page = CI::model ( 'core' )->getParamFromURL ( 'curent_page' );
		
		}
		
		if (intval ( $curent_page ) < 1) {
			
			$curent_page = 1;
		
		}
		
		if ($commented == false) {
			
			$commented = CI::model ( 'core' )->getParamFromURL ( 'commented' );
		
		}
		
		if (($timestamp = strtotime ( $commented )) === false) {
			
		//$this->template ['selected_voted'] = false;
		

		} else {
			
			$posts_data ['commented'] = $commented;
			
		//$this->template ['selected_voted'] = true;
		

		}
		
		if ($voted == false) {
			
			$voted = CI::model ( 'core' )->getParamFromURL ( 'voted' );
		
		}
		
		if (($timestamp = strtotime ( $voted )) === false) {
		
		} else {
			
			$posts_data ['voted'] = $voted;
		
		}
		
		if ($created_by == false) {
			
			$created_by = CI::model ( 'core' )->getParamFromURL ( 'author' );
		
		}
		
		//var_dump($tags);
		

		if (strval ( $created_by ) != '') {
			
			$posts_data ['created_by'] = $created_by;
		
		} else {
			
		//$this->template ['created_by'] = false;
		

		}
		
		if (strval ( $content_layout_name ) != '') {
			
			$posts_data ['content_layout_name'] = $content_layout_name;
		
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
			if (empty ( $order )) {
				$order = CI::model ( 'core' )->getParamFromURL ( 'ord' );
				
				$order_direction = CI::model ( 'core' )->getParamFromURL ( 'ord-dir' );
				
				$orderby1 = array ();
			} else {
				
				$orderby1 = $orderby;
			
			}
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
		
		unset ( $posts_data ['content_subtype_value'] );
		unset ( $posts_data ['content_subtype'] );
		unset ( $posts_data ['visible_on_frontend'] );
		
		//	p($posts_data);
		

		$to_return = array ();
		
		$page_start = ($curent_page - 1) * $items_per_page;
		
		$page_end = ($page_start) + $items_per_page;
		
		if (! empty ( $posts_data )) {
			
			if (is_file ( ACTIVE_TEMPLATE_DIR . 'controllers/pre_posts_get.php' )) {
				
				include_once ACTIVE_TEMPLATE_DIR . 'controllers/pre_posts_get.php';
			
			}
			
			//@todo add getContentAndCache, isted of getContent
			

			$posts_data ['use_fetch_db_data'] = true;
			
			$data = $this->getContentAndCache ( $posts_data, $orderby1, array ($page_start, $page_end ), $short_data = false, $only_fields = array ('id', 'content_title', 'content_body', 'content_url', 'content_filename', 'content_parent', 'content_filename_sync_with_editor', 'content_body_filename' ), true );
			if (! empty ( $data )) {
				$to_return ['posts'] = $data;
			}
			$posts = $data;
			
			$results_count = $this->getContentAndCache ( $posts_data, $orderby1, $limit = false, $count_only = true, $short_data = true, $only_fields = false );
			
			//var_dump($results_count);
			

			$results_count = intval ( $results_count );
			
			$content_pages_count = ceil ( $results_count / $items_per_page );
			
			//var_dump ( $results_count, $items_per_page );
			

			if (! empty ( $data )) {
				$to_return ['count'] = $results_count;
				$to_return ['posts_pages_count'] = $content_pages_count;
				
				$to_return ['posts_pages_curent_page'] = $curent_page;
			}
			//get paging urls
			

			$content_pages = $this->pagingPrepareUrls ( false, $content_pages_count );
			
			//var_dump($content_pages);
			if (! empty ( $data )) {
				
				$to_return ['posts_pages_links'] = $content_pages;
			}
			if (empty ( $data )) {
				return false;
			}
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
		
		$categories = CI::model ( 'core' )->fetchDbData ( 'firecms_taxonomy', $params, $opts );
		
		if (empty ( $categories )) {
			
			return $content;
		
		}
		
		//p($categories,1);
		

		//return;
		

		CI::helper ( 'mw_string' );
		
		$siloLinks = array ();
		
		foreach ( $categories as $category ) {
			
			if ($category ['taxonomy_silo_keywords']) {
				
				$siloLink = array ();
				
				$siloLink ['keywords'] = array ();
				
				$siloLink ['url'] = CI::model ( 'taxonomy' )->getUrlForIdAndCache ( $category ['id'] );
				
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
			
		/*

            $categoryLink = "' <a href=\"{$siloLink['url']}\">'.trim($0).'</a> '";

            $content = preg_replace ( $siloLink ['keywords'], $categoryLink, $content, $linksPerCategory );*/
		
		}
		
		return $content;
	
	}
	
	/**

	 * @desc Get latest content from categories that you define + more flexibility

	 * @param array

	 * @param array

	 * @param boolean

	 * @return array

	 * @author      Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
	function getContentAndCache($data, $orderby = false, $limit = false, $count_only = false, $short_data = true, $only_fields = false) {
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id, 'content/global' );
		
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
			
			CI::model ( 'core' )->cacheWriteAndEncode ( $selected_posts_results, $function_cache_id, 'content/global' );
			
			return $selected_posts_results;
		
		} else {
			
			CI::model ( 'core' )->cacheWriteAndEncode ( 'false', $function_cache_id, 'content/global' );
		
		}
	
	}
	
	function getContent($data, $orderby = false, $limit = false, $count_only = false, $short_data = false, $only_fields = false) {
		
		global $cms_db_tables;
		
		if ($data ['use_fetch_db_data'] == true) {
			
			$use_fetch_db_data = true;
		
		}
		
		$table = $cms_db_tables ['table_content'];
		
		$table_content = $cms_db_tables ['table_content'];
		
		$table_media = $cms_db_tables ['table_media'];
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
		//	$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		

		}
		
		//$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		

		//$cache_content = CI::model('core')->cacheGetContentAndDecode ( $function_cache_id );
		

		$table_taxonomy = $cms_db_tables ['table_taxonomy'];
		$table_taxonomy_items = $cms_db_tables ['table_taxonomy_items'];
		
		if ($orderby == false) {
			
			$orderby [0] = 'updated_on';
			
			$orderby [1] = 'DESC';
		
		}
		
		if (! empty ( $data ['limit'] )) {
			
			$limit = $data ['limit'];
		
		}
		
		if (($data ['count']) == true) {
			
			$count_only = true;
			unset ( $data ['count'] );
		
		}
		
		$ids = array ();
		if ($count_only == false) {
			if (empty ( $limit )) {
				
				$items_per_page = CI::model ( 'core' )->optionsGetByKey ( 'default_items_per_page' );
				
				$items_per_page = intval ( $items_per_page );
				$limit = array (0, $items_per_page );
			}
		}
		
		if (! empty ( $limit )) {
			
			$my_offset = $limit [1] - $limit [0];
			
			$my_limit_q = " limit  {$limit[0]} , $my_offset  ";
		
		} else {
			
			$my_limit_q = false;
			//print "NO LIMMITS@!!!";
		

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
			
			if (! empty ( $categories )) {
				//	p($categories);
				if (is_array ( $categories ) and ! empty ( $categories )) {
					
					$categories_intvals = array ();
					$categories_count = count ( $categories );
					foreach ( $categories as $item ) {
						
						if (intval ( $item ) != 0) {
							$categories_intvals [] = $item;
							$category_ids [] = $item;
						} else {
							$item = CI::model ( 'taxonomy' )->getIdByName ( $item );
							//p($item);
							if (intval ( $item ) != 0) {
								$category_ids [] = $item;
								$more = CI::model ( 'taxonomy' )->getChildrensRecursiveAndCache ( $item, 'category' );
								//p($more);
								if (! empty ( $more )) {
									foreach ( $more as $mor ) {
										//p($mor);
										$category_ids [] = $mor;
									}
								}
							}
						
						}
					}
					//	p($category_ids);
					$only_thise_content = CI::model ( 'taxonomy' )->getToTableIds ( end ( $category_ids ), false );
					
					if (! empty ( $only_thise_content )) {
						foreach ( $only_thise_content as $only_thise_content_i ) {
							$category_ids [] = intval ( $only_thise_content_i );
							$ids [] = intval ( $only_thise_content_i );
						}
					} else {
						$ids = array ();
						$ids [] = 0;
						$ids [] = 'nothing from categories';
						$category_ids [] = 0;
					
					}
				
				}
			
			}
		
		}
		
		if (! empty ( $ids )) {
			array_unique ( $ids );
		}
		
		if (! empty ( $tag_ids )) {
			array_unique ( $tag_ids );
		
		}
		
		if (! empty ( $category_ids )) {
			array_unique ( $category_ids );
		
		}
		
		if ($data ['have_original_link'] == 'y') {
			
			$q = " SELECT id  from   $table_content where  original_link is NOT NULL   and original_link_include_in_advanced_search = 'y' and original_link NOT LIKE '' $my_limit_q ";
			
			$q = CI::model ( 'core' )->dbQuery ( $q, md5 ( $q ), 'content/global' );
			
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
			
			$q = " SELECT id, to_table_id  from   $table_media where  to_table='table_content' and media_type = 'videos' and to_table_id IN ($ids_imploded)  $my_limit_q  ";
			
			$q = CI::model ( 'core' )->dbQuery ( $q, md5 ( $q ), 'media' );
			
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
			
			$kw_ids = false;
			$kw_ids_q = false;
			
			$seach_in_cats = CI::model ( 'taxonomy' )->getIdsByNames ( $categories );
			//
			

			$seach_in_cats = CI::model ( 'taxonomy' )->getToTableIds ( $seach_in_cats, false );
			//p($seach_in_cats);
			if (! empty ( $seach_in_cats )) {
				
				$kw_ids = $seach_in_cats;
				$kw_ids_i = implode ( ',', $kw_ids );
				$kw_ids_q = " and id in ($kw_ids_i) ";
			
			}
			
			$only_in_those_table_fields = array ('content_title', 'content_body', 'content_description' );
			
			//  $keyword_results = CI::model('core')->dbRegexpSearch ( $table, $kw, $kw_ids );
			

			$kw = html_entity_decode ( $kw );
			
			$kw = urldecode ( $kw );
			
			$kw = htmlspecialchars_decode ( $kw );
			
			$the_words_no_explode = ($kw);
			
			//$the_words = explode ( ',', $kw );
			$the_words = array ();
			$the_words [0] = $kw;
			
			if (($kw) != '') {
				if (! empty ( $category_content_ids )) {
					
					$category_ids_q = implode ( ',', $category_content_ids );
					
					$category_ids_q = "  id in ($category_ids_q) and ";
				
				} else {
					
					$category_ids_q = false;
				
				}
				
				$the_search_q_kw3 = $kw;
				$the_search_q = "SELECT id

FROM $table_content

where 

$category_ids_q 





 (( content_title LIKE '%$the_search_q_kw3%' or   
content_description LIKE '%$the_search_q_kw3%'   or   
content_body LIKE '%$the_search_q_kw3%' or 
content_url LIKE '%$the_search_q_kw3%'  ) ) 
 
and content_type='post'
$kw_ids_q

$my_limit_q
                ";
				/*

$the_search_q = "SELECT id

FROM `$table_content`

where $category_ids_q MATCH (`content_title`,`content_description`, `content_body`, `content_url`) AGAINST ('$the_search_q_kw3' in boolean mode)

 

                ";*/
				
				$the_search_q = "SELECT id

FROM $table_content

where ( content_title REGEXP '$the_search_q_kw3' or   
content_description REGEXP '$the_search_q_kw3'   or   
content_body REGEXP '$the_search_q_kw3' or 
content_url REGEXP '$the_search_q_kw3'  )  
 
 $kw_ids_q 



and content_type='post'

ORDER BY id DESC

$my_limit_q


                ";
				//p($the_search_q);
				$queries = array ();
				
				$queries [] = $the_search_q;
				
				$result_ids = array ();
				
				foreach ( $queries as $qq ) {
					$qqq = CI::model ( 'core' )->dbQuery ( $qq, md5 ( $qq ), 'content/global' );
					if (! empty ( $qqq )) {
						foreach ( $qqq as $some_id ) {
							$result_ids [] = $some_id ['id'];
						}
					}
				}
				
				@array_unique ( $result_ids );
				
				$keyword_results = $result_ids;
			
			}
			
			if (! empty ( $result_ids )) {
				unset ( $data ['search_by_keyword'] );
				
				foreach ( $result_ids as $keyword_results_i ) {
					$ids_temp [] = $keyword_results_i;
				}
				$ids = $ids_temp;
			
			} else {
				
				$ids = false;
				
				$ids [] = '0';
			
			}
		
		}
		
		//
		

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
					
					$q = " SELECT id, to_table_id from $table_comments where to_table = 'table_content'

                    $some_ids group by to_table_id  $my_limit_q ";
					
					$q = CI::model ( 'core' )->dbQuery ( $q, md5 ( $q ), 'comments' );
					
					if (! empty ( $q )) {
						
						$only_comments_ids = array ();
						
						foreach ( $q as $itm ) {
							
							$check = true;
							
							//@delete
							

							//$check = $this->contentsCheckIfContentExistsById ( $itm ['to_table_id'] );
							

							if ($check == true) {
								
								$only_comments_ids [] = $itm ['to_table_id'];
							
							} else {
								
								$q1 = "delete from $table_comments where id ={$itm['id']}  ";
								
								$q1 = CI::model ( 'core' )->dbQ ( $q1 );
							
							}
						
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
				
				case 'n' :
					
					$table_comments = $cms_db_tables ['table_comments'];
					
					$table_content = $cms_db_tables ['table_content'];
					
					$q = "SELECT id from $table_content where id not in (

select to_table_id from $table_comments where to_table = 'table_content' group by to_table_id )

                  $my_limit_q  ";
					
					//
					

					$q = CI::model ( 'core' )->dbQuery ( $q, md5 ( $q ), 'comments' );
					
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
		
		} else {
			
			unset ( $data ['with_comments'] );
		
		}
		
		//the voted functionality is built in into CI::model('core')->fetchDbData thats why we romove it from here
		

		if (($timestamp = strtotime ( $data ['voted'] )) !== false) {
			
			$voted = strtotime ( $data ['voted'] . ' ago' );
			
			$table_votes = $cms_db_tables ['table_votes'];
			
			$table_content = $cms_db_tables ['table_content'];
			
			//$pastday = date ( 'Y-m-d H:i:s', mktime ( 0, 0, 0, date ( "m" ), date ( "d" ) - $voted, date ( "Y" ) ) );
			

			$category_ids_implode_q = false;
			if (! empty ( $category_ids )) {
				$category_ids_implode = implode ( ',', $category_ids );
				$category_ids_implode_q = "and to_table_id in ($category_ids_implode)  ";
			} else {
			
			}
			
			$pastday = date ( 'Y-m-d H:i:s', $voted );
			
			$now = date ( 'Y-m-d H:i:s' );
			
			$q = "SELECT count(to_table_id) as qty, to_table_id from $table_votes where

            to_table = 'table_content'
{$category_ids_implode_q}
            and created_on >'$pastday'

            group by to_table_id order by qty desc
$my_limit_q
                    ";
			
			$q = CI::model ( 'core' )->dbQuery ( $q, md5 ( $q ), 'votes/global' );
			
			$only_voted_ids = array ();
			
			if (! empty ( $q )) {
				
				foreach ( $q as $itm ) {
					
					if (intval ( $itm ['qty'] ) > 0) {
						$only_voted_ids [] = $itm ['to_table_id'];
					
					}
				
				}
			
			}
			if ($use_fetch_db_data == false) {
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
				$ids = $only_voted_ids;
			}
		}
		
		if (($data ['voted_by']) != false) {
			foreacH ( $data as $k => $v ) {
				if ($k == 'voted_by') {
					//var_dump($k , $v); 
				}
			}
			$voted_by = strval ( $data ["voted_by"] );
			//$voted_by1 =  end($data["voted_by"]) ;
			

			$table_votes = $cms_db_tables ['table_votes'];
			
			$table_content = $cms_db_tables ['table_content'];
			
			//$pastday = date ( 'Y-m-d H:i:s', mktime ( 0, 0, 0, date ( "m" ), date ( "d" ) - $voted, date ( "Y" ) ) );
			

			$category_ids_implode_q = false;
			if (! empty ( $category_ids )) {
				$category_ids_implode = implode ( ',', $category_ids );
				$category_ids_implode_q = "and to_table_id in ($category_ids_implode)  ";
			} else {
			
			}
			
			$now = date ( 'Y-m-d H:i:s' );
			
			$q = "SELECT count(to_table_id) as qty, to_table_id from $table_votes where

            to_table = 'table_content'
{$category_ids_implode_q}
            and created_by ={$data ['voted_by']}

            group by to_table_id order by qty desc
$my_limit_q
                    ";
			//p ( $q );
			$q = CI::model ( 'core' )->dbQuery ( $q, md5 ( $q ), 'votes/global' );
			//p ( $q );   
			//$only_voted_ids = array ();
			

			if (! empty ( $q )) {
				
				foreach ( $q as $itm ) {
					
					if (intval ( $itm ['qty'] ) > 0) {
						$only_voted_ids [] = $itm ['to_table_id'];
					
					}
				
				}
			
			} else {
				$new_ids = array ();
				$new_ids [] = 0;
				$new_ids [] = 'no voted';
				$ids = $new_ids;
			
			}
			if ($use_fetch_db_data == false) {
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
				
				} else {
				
				}
			}
		}
		
		if (! empty ( $data ['custom_fields_criteria'] )) {
			
			$table_custom_fields = $cms_db_tables ['table_custom_fields'];
			
			$table_content = $cms_db_tables ['table_content'];
			
			$only_custom_fieldd_ids = array ();
			
			$use_fetch_db_data = true;
			
			$ids_q = "";
			
			if (! empty ( $ids )) {
				
				$ids_i = implode ( ',', $ids );
				
				$ids_q = " and to_table_id in ($ids_i) ";
				
			/*foreach ( $ids as $id ) {

                    if (in_array ( $category_content_ids, $id )) {

                    

                    }

                    

                }*/
			
			}
			
			$only_custom_fieldd_ids = array ();
			//	p($data ['custom_fields_criteria'],1);
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
				
				$q = "SELECT  to_table_id from $table_custom_fields where

            to_table = 'table_content' and

            custom_field_name = '$k' and

            custom_field_value = '$v'   $ids_q   $only_custom_fieldd_ids_q 

            
             $my_limit_q

                    ";
				
				$q2 = $q;
				//	p($q);
				$q = CI::model ( 'core' )->dbQuery ( $q, md5 ( $q ), 'custom_fields' );
				//	p($q,1);
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
				
				} else {
					
					//  $ids = array();
					

					$remove_all_ids = true;
					
					$ids = false;
					
					$ids [] = '0';
					
					$ids [] = 0;
				
				}
			
			}
		
		}
		
		if ($remove_all_ids == true) {
			
		//$ids = false;
		

		//$ids [] = '0';
		

		}
		
		if (! empty ( $ids )) {
			
			$ids = array_unique ( $ids );
			$those_ids = $ids;
		}
		
		$flds = $only_fields;
		
		//save is get!!! ?
		

		if (! empty ( $data ['exclude_ids'] )) {
			
			$exclude_ids = $data ['exclude_ids'];
		
		} else {
			
			$exclude_ids = false;
		
		}
		// print 'b4slice'; p($ids);
		if (! empty ( $limit )) {
			
		//$ids = array_slice ( $ids, $limit [0], $limit [1] );
		

		}
		
		if ($use_fetch_db_data == false) {
			
			$flds = array ('id' );
			$data ['search_by_keyword_in_fields'] = $only_in_those_table_fields;
			
			$save = CI::model ( 'core' )->getDbData ( $table = $table, $criteria = $data, $limit, $offset = false, $orderby = $orderby, $cache_group = 'content/global', $debug = false, $ids = $ids, $count_only = $count_only, $flds, $exclude_ids, $force_cache_id = false, $get_only_whats_requested_without_additional_stuff = $short_data );
		
		} else {
			
			$criteria = array ();
			
			foreach ( $data as $k => $v ) {
				$criteria [] = array ($k, $v );
			}
			$deb = false; //debug
			$db_opt = array ();
			$db_opt ['only_fields'] = array ('id' );
			
			if (! empty ( $only_voted_ids )) {
				if (empty ( $ids )) {
					
					$ids = $only_voted_ids;
				} else {
					
					$new_ids = array ();
					foreach ( $only_voted_ids as $only_voted_id ) {
						if (in_array ( $only_voted_id, $ids ) == true) {
							$new_ids [] = $only_voted_id;
						}
					}
					$ids = $new_ids;
					
				//	array_merge($ids,$only_voted_ids);
				}
			
			}
			//p($ids);
			$db_opt ['include_ids'] = $ids;
			
			$db_opt ['exclude_ids'] = $exclude_ids;
			$db_opt ['limit'] = $limit;
			$db_opt ['get_count'] = $count_only;
			$db_opt ['search_keyword'] = $search_for_those_kw_in_fetch;
			$db_opt ['search_keyword_only_in_those_fields'] = $only_in_those_table_fields;
			$db_opt ['debug'] = false;
			$db_opt ['cache_group'] = 'content/global';
			$db_opt ['order'] = $orderby;
			
			//	p($criteria);
			

			$save = CI::model ( 'core' )->fetchDbData ( $table, $criteria, $db_opt );
		
		}
		
		if ($count_only == true) {
			
			//CI::model('core')->cacheWriteAndEncode ( $save, $function_cache_id, $cache_group = 'content' );
			

			return $save;
		
		}
		
		$media_url = MEDIA_URL;
		
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
						
						}
						
						if ($k == 'id') {
							
							$media = CI::model ( 'core' )->mediaGet ( 'table_content', $v );
							
							if (! empty ( $media )) {
								
								$item ['media'] = $media;
							
							} else {
								
								$item ['media'] = false;
							
							}
						
						}
						
						if ($k == 'content_body') {
							
							if (is_array ( $v ) == false) {
								
								/*if ($item ['content_filename_sync_with_editor'] == 'y') {

                                    if (trim ( $item ['content_filename'] ) != '') {

                                        $the_active_site_template = CI::model('core')->optionsGetByKey ( 'curent_template' );

                                        $the_active_site_template_dir = TEMPLATEFILES . $the_active_site_template . '/';

                                        if (is_file ( $the_active_site_template_dir . $item ['content_filename'] ) == true) {

                                            {

                                                //  var_dump ( $the_active_site_template_dir );

                                            //$v = $the_active_site_template_dir;

                                            //$v = file_get_contents ( $the_active_site_template_dir . $item ['content_filename'] );

                                            //print $v;

                                            //exit;

                                            }

                                        }



                                    }



                                }*/
								
								if ($item ['content_body_filename'] != false) {
									
									if (trim ( $item ['content_body_filename'] ) != '') {
										
										$the_active_site_template = CI::model ( 'core' )->optionsGetByKey ( 'curent_template' );
										
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
									
									$item ['the_content_body'] = $desc;
									
									// Silo linking
									

									// This field is used only for content visualization and it's not used by admin panel
									

									if (CI::model ( 'core' )->optionsGetByKey ( 'enable_silo_linking' )) {
										
									//$item ['the_content_body'] = $this->contentsAddSiloLinks ( $item ['the_content_body'] );
									

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
					
					if ($k == 'content_body') {
						
						$desc = htmlspecialchars_decode ( $v, ENT_QUOTES );
						$desc = str_ireplace ( '{MEDIAURL}', $media_url, $desc );
						
						$desc = str_ireplace ( '{SITEURL}', site_url (), $desc );
						//print $desc;
						$item2 ['the_content_body'] = $desc;
					}
					
					$item2 [$k] = $v;
				
				}
				
				$return2 [] = $item2;
			
			}
			
			return $return2;
		
		} else {
			
			return false;
		
		}
	
	}
	
	function pagingPrepareUrls($base_url = false, $pages_count, $paging_param = 'curent_page', $keyword_param = 'keyword') {
		
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
				
				if ($itm [0] == $paging_param) {
					
					$itm [1] = $x;
				
				}
				
				$new [] = implode ( ':', $itm );
			
			}
			
			$new_url = implode ( '/', $new );
			
			//var_dump ( $new_url);
			

			$page_links [$x] = $new_url;
		
		}
		
		for($x = 1; $x <= count ( $page_links ); $x ++) {
			
			if (stristr ( $page_links [$x], $paging_param . ':' ) == false) {
				
				$page_links [$x] = reduce_double_slashes ( $page_links [$x] . '/' . $paging_param . ':' . $x );
			
			}
		
		}
		
		return $page_links;
	
	}
	
	function getBlogSections() {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_content'];
		
		$orderby [0] = 'updated_on';
		
		$orderby [1] = 'DESC';
		
		$data = array ();
		
		$data ['content_subtype'] = 'blog_section';
		
		$data ['content_type'] = 'page';
		
		$get = CI::model ( 'core' )->getDbData ( $table = $table, $criteria = $data, $limit = false, $offset = false, $orderby = $orderby, $cache_group = 'content', $debug = false, $ids = false );
		
		return $get;
	
	}
	
	function define_vars() {
	
	}
	
	function applyGlobalTemplateReplaceables($content, $replaceables = false) {
		static $mem = array ();
		$layout_md5 = md5 ( $content );
		$options_md5 = md5 ( serialize ( $replaceables ) );
		$check = $layout_md5 . $options_md5;
		if ($mem ["{$check}"] != false) {
			return $mem [$check];
		}
		//return false;
		

		//$is_post = $this->template['post'];
		

		if (strstr ( $content, 'content_meta_title' )) {
			$is_content = $this->template ['post'];
			
			if ($is_content == false) {
				$is_content = $this->template ['page'];
			}
			//p($is_content);
			if ($is_content ['content_meta_title']) {
				$content_meta_title = $is_content ['content_meta_title'];
			} elseif ($is_content ['content_title']) {
				$content_meta_title = codeClean ( $is_content ['content_title'] );
			} else {
				$content_meta_title = CI::model ( 'core' )->optionsGetByKey ( 'content_meta_title' );
			}
		}
		if (strstr ( $content, 'content_meta_description' )) {
			$content_meta_description = CI::model ( 'core' )->optionsGetByKey ( 'content_meta_description' );
		}
		if (strstr ( $content, 'content_meta_keywords' )) {
			$content_meta_keywords = CI::model ( 'core' )->optionsGetByKey ( 'content_meta_keywords' );
		}
		if (strstr ( $content, 'content_meta_other_code' )) {
			$content_meta_other_code = CI::model ( 'core' )->optionsGetByKey ( 'content_meta_other_code' );
		}
		
		$global_replaceables = array ();
		
		$global_replaceables ["content_meta_title"] = $content_meta_title;
		
		$global_replaceables ["content_meta_description"] = codeClean ( $content_meta_description );
		
		$global_replaceables ["content_meta_keywords"] = $content_meta_keywords;
		
		//$global_replaceables ["content_meta_other_code"] = $content_meta_other_code;
		

		$global_replaceables ["content_meta_other_code"] = ($global_replaceables ["content_meta_other_code"]);
		
		if (trim ( $content ["content_meta_other_code"] ) != '') {
			$content ["content_meta_other_code"] = ($content ["content_meta_other_code"]);
		}
		
		$content_to_return = false;
		
		foreach ( $global_replaceables as $k => $v ) {
			
			//if (array_key_exists ( $k, $replaceables ) == true) {
			

			$v = $replaceables [$k];
			
			if (strval ( $v ) == '') {
				
				$v = $global_replaceables [$k];
			
			}
			
			$content = str_replace ( '{' . $k . '}', $v, $content );
			//$content =  sprintf('{' . $k . '}', $v,$content);
		//$CI = get_instance ();
		

		//}
		

		}
		
		$media_url = MEDIA_URL;
		$content = str_ireplace ( '{MEDIAURL}', $media_url, $content );
		
		$content = str_ireplace ( '{SITEURL}', site_url (), $content );
		
		$content = str_ireplace ( '{SITE_URL}', site_url (), $content );
		
		$content = str_ireplace ( '{JS_API_URL}', site_url ( 'api/js' ) . '/', $content );
		
		$content = str_ireplace ( '{API_URL}', site_url ( 'api' ) . '/', $content );
		$mem [$check] = $content;
		return $content;
	
	}
	
	//this funtion is used in the backend (admin) its diferent from the one in templates model
	

	function getLayoutFiles() {
		
		CI::helper ( 'directory' );
		
		//$path = BASEPATH . 'content/templates/';
		

		$the_active_site_template = CI::model ( 'core' )->optionsGetByKey ( 'curent_template' );
		
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
	
	function taxonomyGetUrlForId($id) {
		
		//return false ;
		

		if (intval ( $id ) == 0) {
			
			return false;
		
		}
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$taxonomy_id = intval ( $id );
		$cache_group = 'taxonomy/' . $taxonomy_id;
		
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		} else {
			
			$data = array ();
			
			$data ['id'] = $id;
			
			$data = CI::model ( 'taxonomy' )->getSingleItem ( $id );
			
			if (empty ( $data )) {
				
				return false;
			
			}
			//$this->load->model ( 'Content_model', 'content_model' );
			global $cms_db_tables;
			//global $CI;
			

			$table = $cms_db_tables ['table_taxonomy'];
			$table_content = $cms_db_tables ['table_content'];
			
			$content = array ();
			
			$content ['content_subtype'] = 'blog_section';
			
			$content ['content_subtype_value'] = $id;
			
			//$orderby = array ('id', 'desc' );
			

			//$q = " select * from $table_content where content_subtype ='blog_section' and content_subtype_value={$id} limit 0,1";
			

			//$q = CI::model('core')->dbQuery ( $q, __FUNCTION__ . md5 ( $q ), $cache_group );
			

			$content = $this->getContentAndCache ( $content, $orderby );
			
			//$content = $q [0];
			$content = $content [0];
			
			$url = false;
			
			if (! empty ( $content )) {
				
				if ($content ['content_type'] == 'page') {
					
					$url = page_link ( $content ['id'] );
				
				}
				
				if ($content ['content_type'] == 'post') {
					
					$url = post_link ( $content ['id'] );
				
				}
			
			}
			
			if ($url != false) {
				
				return $url;
			
			}
			
			$parent_ids = CI::model ( 'taxonomy' )->getParentsIds ( $data ['id'] );
			
			foreach ( $parent_ids as $item ) {
				
				$content = array ();
				
				$content ['content_subtype'] = 'blog_section';
				
				$content ['content_subtype_value'] = $item;
				
				$orderby = array ('id', 'desc' );
				
				$q = " select * from $table_content where content_subtype ='blog_section' and content_subtype_value={$item} limit 0,1";
				
				//$q = CI::model('core')->dbQuery ( $q, __FUNCTION__ . md5 ( $q ), $cache_group );
				$content = $this->getContentAndCache ( $content, $orderby );
				//$content = CI::model('content')->getContentAndCache ( $content, $orderby );
				

				//$content = $q [0];
				

				$content = $content [0];
				
				$url = false;
				
				if (! empty ( $content )) {
					
					if ($content ['content_type'] == 'page') {
						
						$url = page_link ( $content ['id'] );
						//$url = $url . '/category:' . $data ['taxonomy_value'];
						$url = $url . '/categories:' . $data ['id'];
					
					}
					if ($content ['content_type'] == 'post') {
						
						$url = post_link ( $content ['id'] );
					
					}
				}
				
				if ($url != false) {
					CI::model ( 'core' )->cacheWriteAndEncode ( $url, $function_cache_id, $cache_group );
					return $url;
				
				}
			
			}
			
			return false;
		}
		//var_dump ( $parent_ids );
	

	}
	
	function deleteMenuItem($id) {
		if (intval ( $id ) == 0) {
			
			return false;
		
		}
		
		$table = TABLE_PREFIX . 'menus';
		
		$id1 = "SELECT * from $table where id={$id} limit 1  ";
		$id1 = CI::model ( 'core' )->dbQuery ( $id1 );
		$id1 = $id1 [0];
		if (! empty ( $id1 )) {
			$shift_parent = intval ( $id1 ['item_parent'] );
			
			$id2 = "UPDATE $table set item_parent={$shift_parent} where id={$id}  ";
			$id2 = CI::model ( 'core' )->dbQ ( $id2 );
			
			$data = array ();
			$data ['id'] = $id;
			$del = CI::model ( 'core' )->deleteData ( $table, $data, 'menus' );
			return true;
		}
		return false;
	}
	
	function saveMenuItem($data) {
		
		if (empty ( $data )) {
			
			return false;
		
		}
		
		$table = TABLE_PREFIX . 'menus';
		if (intval ( $data ['id'] ) != 0) {
			$check_if_exists = "SELECT count(id) as qty from $table where id='{$data ['id']}' limit 1  ";
			$check = CI::model ( 'core' )->dbQuery ( $check_if_exists );
			
			$check = $check [0] ['qty'];
			//p($check);
			if (intval ( $check ) == 0) {
				$check_if_exists = " INSERT into $table set id={$data ['id']}";
				$insert = CI::model ( 'core' )->dbQ ( $check_if_exists );
			}
		}
		$save = CI::model ( 'core' )->saveData ( $table, $data );
		return $save;
	
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
			
			$check = CI::model ( 'core' )->getDbData ( $table, $check_if_exists, $limit = false, $offset = false, $orderby, $cache_group = 'menus' );
			
			$check = $check [0];
			
			if (! empty ( $check )) {
				
				$data ['id'] = $check ['id'];
			
			}
			
			if (intval ( $check ['position'] ) == 0) {
				
				//find position
				

				$sql = "SELECT max(position) as maxpos from $table where item_parent='{$data ['item_parent']}'  ";
				
				$q = CI::model ( 'core' )->sqlQuery ( $sql, 'menus' );
				
				$result = $q [0];
				
				$maxpos = intval ( $result ['maxpos'] ) + 1;
				
				$data ['position'] = $maxpos;
			
			}
		
		}
		
		$data_to_save = $data;
		
		$data_to_save ['cache_group'] = 'menus';
		
		$data_to_save ['model_group'] = strtolower ( get_class () );
		
		if ($data_to_save ['menu_url']) {
			
			$valid = true;
			if ($valid == false) {
				
				unset ( $data_to_save ['menu_url'] );
			
			} else {
			
			}
			
			$data_to_save ['item_type'] = $data ['item_type'];
		
		}
		
		$data_to_save_options = array ();
		
		$data_to_save_options ['delete_cache_groups'] = array ('menus' );
		
		$table = TABLE_PREFIX . 'menus';
		
		$save = CI::model ( 'core' )->saveData ( $table, $data_to_save );
		
		CI::model ( 'core' )->cleanCacheGroup ( 'menus' );
		
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
		
		$save = CI::model ( 'core' )->getDbData ( $table, $data, $limit = false, $offset = false, $orderby, $cache_group = 'menus' );
		
		if ($data ['item_type'] == 'menu' and count ( $data ) == 1) {
			//p($save);
			if (empty ( $save )) {
				$add_main_menu = array ();
				
				$add_main_menu ['item_type'] = 'menu';
				$add_main_menu ['item_title'] = 'Main menu';
				$add_main_menu ['menu_description'] = 'Main menu';
				$add_main_menu ['menu_title'] = 'Main menu';
				$add_main_menu ['menu_unique_id'] = 'main_menu';
				
				$add_main_menu ['is_active'] = 'y';
				$this->saveMenu ( $add_main_menu );
				$save = CI::model ( 'core' )->getDbData ( $table, $data, $limit = false, $offset = false, $orderby, $cache_group = 'menus' );
			
			}
		}
		return $save;
	
	}
	
	function getBreadcrumbsByURLAndPrintThem($the_url = false, $include_home = true) {
		
		//return false;
		

		if ($the_url != false) {
			
		//$the_url = $the_url;
		

		} else {
			
			$the_url = getCurentURL ();
		
		}
		
		$quick_nav = $this->getBreadcrumbsByURLAsArray ( $the_url, $include_home );
		
		 
		if (! empty ( $quick_nav )) {
			
			?>

<ul class="breadcrumb">

                     <?php
			foreach ( $quick_nav as $item ) {
				
				?>

                        <li><a
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
		
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id );
		
		if (($cache_content) != false) {
			
		//	return $cache_content;
		

		}
		
		if (is_array ( $the_url )) {
			
			$the_page = $the_url;
		
		} else {
			
			$the_pages_nav = array ();
			
			$the_nav = array ();
			
			$the_page = $this->getContentByURL ( $the_url );
		
		}
		
		//$active_categories = CI::model('taxonomy')->getCategoriesForContent ( $post ['id'], $return_only_ids = true );
		

		$the_page_full = $the_page;
		
		$the_pages_nav [] = $the_page ['id'];
		
		$the_page = $the_page ['id'];
		
		if (intval ( $the_page ) != 0) {
			
			if ($the_page_full ['content_type'] == 'post') {
				
			//$parent_ids = $this->getParentPagesIdsForPageIdAndCache ( $the_page_full['content_parent'] );
			//p($parent_ids);
			

			} else {
				
				$parent_ids = $this->getParentPagesIdsForPageIdAndCache ( $the_page );
				
				if (! empty ( $parent_ids )) {
					
					foreach ( $parent_ids as $item ) {
						
						$the_pages_nav [] = $item;
					
					}
				
				}
			
			}
		
		}
		
		/*  $parent_ids = $this->getParentPagesIdsForPageIdAndCache ( $the_page );

        if (! empty ( $parent_ids )) {

            foreach ( $parent_ids as $item ) {

                $the_pages_nav [] = $item;

            }

        }

        */
		
		$home_page = $this->getContentHomepage ();
		
		$home_page_url = $this->getContentURLByIdAndCache ( $home_page ['id'] );
		
		if ($include_home == true) {
			
			$temp = array ();
			
			$temp ['content_id'] = $home_page ['id'];
			
			$temp ['title'] = $home_page ['content_title'];
			
			$temp ['url'] = $home_page_url;
			
			$the_nav [] = $temp;
		
		}
		
		$active_categories_for_nav = CI::model ( 'content' )->contentActiveCategoriesForPageIdAndCache ( $the_page, $the_url );
		
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
							
							if (! empty ( $some_categories )) {
								
								$some_categories = array_reverse ( $some_categories );
								
								foreach ( $some_categories as $item ) {
									
									$cat_url = CI::model ( 'taxonomy' )->getUrlForId ( $item );
									
									$cat_info = CI::model ( 'taxonomy' )->getSingleItem ( $item );
									
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
								
								}
							
							}
						
						}
						
						$the_nav [] = $temp;
						
						//if (intval ( $page ['content_subtype_value'] ) != 0) {
						

						if ($page ['content_subtype'] == 'blog_section') {
							
							$active_categories = $this->contentActiveCategoriesForPageIdAndCache ( $page ['id'], $the_url, true );
							
							if (! empty ( $active_categories )) {
								
								$active_categories_children = CI::model ( 'taxonomy' )->getParents ( $active_categories [0] );
								
								if (! empty ( $active_categories_children )) {
									
									$active_categories_children = array_reverse ( $active_categories_children );
									
									$active_categories = $active_categories_children;
								
								}
								
								foreach ( $active_categories as $item ) {
									
									$cat_url = CI::model ( 'taxonomy' )->getUrlForId ( $item );
									
									$cat_info = CI::model ( 'taxonomy' )->getSingleItem ( $item );
									
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
							
						/*$item = $page ['content_subtype_value'];



*/
						
						}
						
					//}
					

					//var_dump ( $page );
					

					}
				
				}
			
			}
			
			if (! empty ( $active_categories_for_nav )) {
				
				foreach ( $active_categories_for_nav as $item ) {
					
					if (! in_array ( $item, $categories_already_shown )) {
						
						$categories_already_shown [] = strtolower ( $item );
						
						$cat_url = CI::model ( 'taxonomy' )->getUrlForId ( $item );
						
						$cat_info = CI::model ( 'taxonomy' )->getSingleItem ( $item );
						
						$cat_temp = array ();
						
						$cat_temp ['taxonomy_id'] = $item;
						
						if (! in_array ( strtolower ( $cat_info ['taxonomy_value'] ), $titles_already_shown )) {
							
							$cat_temp ['title'] = $cat_info ['taxonomy_value'];
							
							$titles_already_shown [] = $cat_info ['taxonomy_value'];
							
							$cat_temp ['url'] = $cat_url;
							
							$the_nav [] = $cat_temp;
						
						}
					
					}
				
				}
			
			}
		
		}
		
		$the_nav2 = array ();
		$titles_already_shown = array ();
		foreach ( $the_nav as $item ) {
			if (strval ( $item ['title'] ) != '') {
				if (! in_array ( strtolower ( $item ['title'] ), $titles_already_shown )) {
					$titles_already_shown [] = strtolower ( $item ['title'] );
					$the_nav2 [] = $item;
				}
			}
			//var_dump($item);
		

		}
		$the_nav = $the_nav2;
		CI::model ( 'core' )->cacheWriteAndEncode ( $the_nav, $function_cache_id, $cache_group = 'global' );
		
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
	function getMenuIdByName($name) {
		$name = trim ( $name );
		
		if ($name == '') {
			return false;
		}
		
		$args = func_get_args ();
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id, 'menus' );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		$data = false;
		
		$data ['menu_unique_id'] = $name;
		
		$data = $this->getMenus ( $data, $orderby = false );
		
		$data = $data [0];
		
		$id = $data ['id'];
		$id = intval ( $id );
		if ($id == 0) {
			$sav = array ();
			$sav ['menu_unique_id'] = $name;
			$sav ['item_type'] = 'menu';
			$id = $this->saveMenu ( $sav );
		
		}
		CI::model ( 'core' )->cacheWriteAndEncode ( $id, $function_cache_id, $cache_group = 'menus' );
		
		return $id;
	
	}
	function getMenuItemsByMenuUnuqueId($uid, $set_active_to_all = true) {
		
		$check = getCurentURL ();
		
		$function_cache_id = false;
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $check . $function_cache_id );
		
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id, 'menus' );
		
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
			
			$parents = $this->getParentPagesIdsForPageIdAndCache ( ACTIVE_PAGE_ID );
			
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
		
		CI::model ( 'core' )->cacheWriteAndEncode ( $menu_data, $function_cache_id, $cache_group = 'menus' );
		
		return $menu_data;
	
	}
	function menuTree($menu_id, $maxdepth = false) {
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id, 'menus' );
		
		if (($cache_content) != false) {
			//p($cache_content);
			return $cache_content;
		
		}
		
		//$data = $this->getMenuItems ( $menu_id );
		//p($data);
		$time_start = microtime_float ();
		
		// Sleep for a while
		

		$time_end = microtime_float ();
		$time = $time_end - $time_start;
		
		$passed_ids = array ();
		
		global $cms_db_tables;
		
		$table_menus = $cms_db_tables ['table_menus'];
		
		$sql = "SELECT id from $table_menus where item_parent=$menu_id  and $table_menus.item_parent<>$table_menus.id  order by position ASC ";
		//p ( $sql );
		$q = CI::model ( 'core' )->dbQuery ( $sql, __FUNCTION__ . md5 ( $sql ), 'menus' );
		
		$data = $q;
		if (empty ( $data )) {
			return false;
		}
		//$to_print = '<ul class="menu" id="menu_item_' .$menu_id . '">';
		$to_print = '<ul class="menu menu_' . $menu_id . '">';
		$cur_depth = 0;
		foreach ( $data as $item ) {
			$full_item = $this->getMenuItems ( false, $item ['id'] );
			$full_item = $full_item [0];
			//p($full_item);
			

			if ($full_item ['item_title'] == '') {
				$full_item ['item_title'] = $full_item ['id'];
			}
			
			$to_print .= '<li class="menu_element" id="menu_item_' . $item ['id'] . '"><a href="' . $full_item ['the_url'] . '">' . $full_item ['item_title'] . '</a></li>';
			
			if (in_array ( $item ['id'], $passed_ids ) == false) {
				
				if ($maxdepth == false) {
					$test1 = $this->menuTree ( $item ['id'] );
					if (strval ( $test1 ) != '') {
						$to_print .= strval ( $test1 );
					}
				} else {
					
					if (($maxdepth != false) and ($cur_depth <= $maxdepth)) {
						$test1 = $this->menuTree ( $item ['id'] );
						if (strval ( $test1 ) != '') {
							$to_print .= strval ( $test1 );
						}
					
					}
				}
			}
			
			$passed_ids [] = $item ['id'];
			//}
			//}
			$cur_depth ++;
		}
		
		//print "[[ $time ]]seconds\n";
		$to_print .= '</ul>';
		CI::model ( 'core' )->cacheWriteAndEncode ( $to_print, $function_cache_id, 'menus' );
		return $to_print;
	}
	function getMenuItemById($id) {
		$id = $this->getMenuItems ( $menu_id = false, $id );
		$id = $id [0];
		return $id;
	
	}
	function getMenuItems($menu_id = false, $id = false) {
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id, 'menus' );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		global $cms_db_tables;
		
		$table_menus = $cms_db_tables ['table_menus'];
		
		$table_content = $cms_db_tables ['table_content'];
		
		$data = false;
		if ($id == false) {
			$data ['item_parent'] = $menu_id;
			$data ['item_type'] = 'menu_item';
		} else {
			$data ['id'] = $id;
		}
		
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
						
						$q = CI::model ( 'core' )->dbQuery ( $q, md5 ( $q ), 'content/' . $item ['content_id'] );
						
						$fix_title = $q [0] ['content_title'];
						
						//  print $fix_title;
						

						$item ['item_title'] = $fix_title;
						
						$q = "update $table_menus set item_title='$fix_title' where id={$item['id']}";
						
						CI::model ( 'core' )->dbQ ( $q );
						
					//  var_Dump ( $q );
					

					//  exit ();
					

					}
				
				}
			
			} 

			elseif ($item ['taxonomy_id'] != 0) {
				
				if (strval ( $item ['item_title'] ) == '') {
					
					$get_taxonomy = CI::model ( 'taxonomy' )->getSingleItem ( $item ['taxonomy_id'] );
					
					$fix_title = $get_taxonomy ['taxonomy_value'];
					
					$item ['item_title'] = $fix_title;
					
					$q = "update $table_menus set item_title='$fix_title' where id={$item['id']}";
					
					CI::model ( 'core' )->dbQ ( $q );
					
				//  var_Dump ( $q );
				

				//  exit ();
				

				}
				
				//
				

				$url = CI::model ( 'taxonomy' )->getUrlForId ( $item ['taxonomy_id'] );
			
			} else {
				
				$url = trim ( $item ['menu_url'] );
			
			}
			
			$item ['the_url'] = $url;
			
			//$sub_items = CI::model ( 'content' )->getMenuItems($item ['id']);
			

			if (! empty ( $sub_items )) {
				//$item ['submenu'] = $sub_items;
			

			}
			
			$return [] = $item;
		
		}
		
		CI::model ( 'core' )->cacheWriteAndEncode ( $return, $function_cache_id, 'menus' );
		
		return $return;
	
	}
	
	function menusGetThumbnailImageById($id, $size = 128, $direction = "DESC") {
		
		//$data ['id'] = $id;
		

		//$data = CI::model('taxonomy')->taxonomyGet ( $data );
		

		//var_dump ( $data );
		

		$data = CI::model ( 'core' )->mediaGetThumbnailForItem ( $to_table = 'table_menus', $to_table_id = $id, $size, $direction );
		
		return $data;
	
	}
	
	function getMenuItemsByMenuName($name) {
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id );
		
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
		

		CI::model ( 'core' )->cacheWriteAndEncode ( $menu_data, $function_cache_id );
		
		return $menu_data;
	
	}
	
	function reorderMenuItem($direction, $id) {
		
		$this->fixMenusPositions ();
		
		if (intval ( $id ) == 0) {
			
			//$this->fixMenusPositions ();
			

			return false;
		
		}
		
		$data = false;
		
		CI::model ( 'core' )->cleanCacheGroup ( 'menus' );
		
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
		
		CI::model ( 'core' )->cleanCacheGroup ( 'menus' );
		
		$data ['item_parent'] = $curent_item ['item_parent'];
		
		$data ['item_type'] = $curent_item ['item_type'];
		
		$data ['position'] = intval ( $curent_item ['position'] ) + 1;
		
		$data = $this->getMenus ( $data );
		
		$next_item = $data [0];
		
		//get prev
		

		CI::model ( 'core' )->cleanCacheGroup ( 'menus' );
		
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
				
				$sql_q = CI::model ( 'core' )->dbQ ( $q );
				
				$q = "UPDATE $table set position={$curent_item['position']} where id={$prev_item['id']}   ";
				
				$sql_q = CI::model ( 'core' )->dbQ ( $q );
				
				CI::model ( 'core' )->cleanCacheGroup ( 'menus' );
				
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
				
				$sql_q = CI::model ( 'core' )->dbQ ( $q );
				
				$q = "UPDATE $table set position={$curent_item['position']} where id={$next_item['id']}   ";
				
				$sql_q = CI::model ( 'core' )->dbQ ( $q );
				
				CI::model ( 'core' )->cleanCacheGroup ( 'menus' );
				
				$this->fixMenusPositions ();
				
				return true;
			
			}
		
		}
	
	}
	
	/**

	 * @desc fix Menus Positions

	 * @author Peter Ivanov

	 *

	 */
	
	function fixMenusPositions($menu_id = false) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_menus'];
		
		$table_content = $cms_db_tables ['table_content'];
		
		//$table = TABLE_PREFIX . 'menus';
		

		//get all menus
		

		if ($menu_id == false) {
			$sql = "SELECT * from $table where item_type='menu'  ";
		} else {
			$sql = $sql = "SELECT id from $table where item_parent=$menu_id  and $table.item_parent<>$table.id  ";
		}
		$q = CI::model ( 'core' )->sqlQuery ( $sql );
		
		$results = $q;
		
		foreach ( $results as $item ) {
			
			$sql = "SELECT * from $table where   item_parent='{$item['id']}' order by position ASC ";
			
			$q = CI::model ( 'core' )->sqlQuery ( $sql );
			
			$i = 1;
			
			foreach ( $q as $menu_item ) {
				
				$query = " update $table set position=$i where id={$menu_item['id']} ";
				
				$query = CI::db ()->query ( $query );
				
				$i ++;
			
			}
		
		}
		
		CI::model ( 'core' )->cleanCacheGroup ( 'menus' );
		
		return true;
	
	}
	
	function removeContentFromUnusedMenus($content_id, $menus_array) {
		
		if (empty ( $menus_array )) {
			
			return false;
		
		}
		
		$table = TABLE_PREFIX . 'menus';
		
		$menus_array = implode ( ',', $menus_array );
		
		$q = "DELETE FROM $table where content_id=$content_id and item_parent NOT IN ($menus_array)  ";
		
		$sql_q = CI::db ()->query ( $q );
		
		CI::model ( 'core' )->cleanCacheGroup ( 'menus' );
		
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
		
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		}
		
		if (! empty ( $selected_taxonomy )) {
			
			$selected_taxonomy_titles = array ();
			
			$selected_taxonomy_descs = array ();
			
			//$data = CI::model('taxonomy')->taxonomyGenerateTagsFromString ( $data, 3 );
			

			//$meta ['content_meta_keywords'] = $data;
			

			foreach ( $selected_taxonomy as $the_taxonomy ) {
				
				$the_taxonomy_full = CI::model ( 'taxonomy' )->getSingleItem ( $the_taxonomy );
				
				$selected_taxonomy_titles [] = $the_taxonomy_full ['taxonomy_value'];
				
				$selected_taxonomy_descs [] = $the_taxonomy_full ['taxonomy_description'];
			
			}
			
			$meta = array ();
			
			$meta ["content_meta_title"] = implode ( ', ', $selected_taxonomy_titles );
			
			$meta ["content_meta_description"] = implode ( ', ', $selected_taxonomy_descs );
			
			//			$keyrods = CI::model('taxonomy')->taxonomyGenerateTagsFromString ( implode ( ', ', $selected_taxonomy_descs ), 3 );
			

			$meta ["content_meta_keywords"] = $keyrods;
			
			CI::model ( 'core' )->cacheWriteAndEncode ( $meta, $function_cache_id, $cache_group );
			
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
			
			$meta ["content_meta_title"] = ($content ['content_meta_title'] != '') ? $content ['content_meta_title'] : CI::model ( 'core' )->optionsGetByKey ( 'content_meta_title' );
			
			$meta ["content_meta_description"] = ($content ['content_meta_description'] != '') ? $content ['content_meta_description'] : CI::model ( 'core' )->optionsGetByKey ( 'content_meta_description' );
			
			$meta ["content_meta_keywords"] = ($content ['content_meta_keywords'] != '') ? $content ['content_meta_keywords'] : CI::model ( 'core' )->optionsGetByKey ( 'content_meta_keywords' );
			
			$meta ["content_meta_other_code"] = ($content ['content_meta_other_code'] != '') ? $content ['content_meta_other_code'] : CI::model ( 'core' )->optionsGetByKey ( 'content_meta_other_code' );
			
			CI::model ( 'core' )->cacheWriteAndEncode ( $meta, $function_cache_id, $cache_group );
			
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
				
				//				$data = CI::model('taxonomy')->taxonomyGenerateTagsFromString ( $data, 3 );
				

				$meta ['content_meta_keywords'] = $data;
				
				if ($meta ['content_meta_keywords'] == '') {
					
					if ($content ['content_subtype'] == 'blog_section') {
						
						$temp = CI::model ( 'taxonomy' )->getChildrensRecursiveAndCache ( $content ['content_subtype_value'], 'category' );
						
						if (! empty ( $temp )) {
							
							$taxonomy_tree = array ();
							
							foreach ( $temp as $tax_id ) {
								
								$temp_data = CI::model ( 'taxonomy' )->getSingleItem ( $tax_id );
								
								$taxonomy_tree [] = trim ( $temp_data ['taxonomy_value'] );
							
							}
							
							$taxonomy_tree = array_unique ( $taxonomy_tree );
							
							$taxonomy_tree = implode ( ', ', $taxonomy_tree );
							
							$meta ['content_meta_keywords'] = $taxonomy_tree;
							
							if ($meta ['content_meta_description'] == '') {
								
								$meta ['content_meta_description'] = $meta ['content_meta_keywords'];
							
							}
							
							if (! empty ( $posts_data )) {
								
								$temp_data = CI::model ( 'taxonomy' )->getSingleItem ( $selected_taxonomy [0] );
								
								$meta ['content_meta_title'] = $temp_data ['taxonomy_value'];
								
								$categories = array ();
								
								foreach ( $posts_data as $thepost ) {
									
									$keyword_calculate = $keyword_calculate . ' ' . $thepost ['content_title'] . ' ' . $thepost ['content_body'];
								
								}
								
								$keyword_calculate = CI::model ( 'taxonomy' )->taxonomyGenerateTagsFromString ( $keyword_calculate );
								
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
			
			CI::model ( 'core' )->cacheWriteAndEncode ( $meta, $function_cache_id, $cache_group );
			
			return $meta;
		
		}
	
	}
	
	function contentGetRecomendedPosts($post_id, $how_much = 5) {
		
		$function_cache_id = serialize ( $post_id ) . serialize ( $how_much );
		
		$function_cache_id = __FUNCTION__ . md5 ( __FUNCTION__ . $function_cache_id );
		
		$content_id = intval ( $post_id );
		$cache_group = 'content/' . $content_id;
		
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
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
					
					$search_in_content = CI::model ( 'core' )->dbRegexpSearch ( $table, $search_string, $only_in_ids = false, $only_in_those_table_fields = $only_in_those_table_fields );
					
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
					
					$search_in_content = CI::model ( 'core' )->dbRegexpSearch ( $table, $search_string, $only_in_ids = false, $only_in_those_table_fields = $only_in_those_table_fields );
					
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
					
					$search_in_content = CI::model ( 'core' )->dbRegexpSearch ( $table, $search_string, $only_in_ids = false, $only_in_those_table_fields = $only_in_those_table_fields );
					
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
					
					$search_in_content = CI::model ( 'core' )->dbRegexpSearch ( $table, $search_string, $only_in_ids = false, $only_in_those_table_fields = $only_in_those_table_fields );
					
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
					
					$search_in_content = CI::model ( 'core' )->dbRegexpSearch ( $table, $search_string, $only_in_ids = false, $only_in_those_table_fields = $only_in_those_table_fields );
					
					if (! empty ( $search_in_content )) {
						
						foreach ( $search_in_content as $item ) {
							
							$the_results_to_return [] = $item;
						
						}
					
					}
				
				}
			
			}
			
		//}
		

		}
		
		$the_results_to_return_revalency = array ();
		
		foreach ( $the_results_to_return as $item ) {
			
			if (intval ( $item ) != intval ( $post_id )) {
				
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
			
			CI::model ( 'core' )->cacheWriteAndEncode ( $posts, $function_cache_id, $cache_group );
			
			return $posts;
		
		} else {
			
			return false;
		
		}
		
		return $posts;
		
	//var_dump ( $the_results_to_return_revalency );
	

	}
	
	function content_helpers_getPagesAsUlTree($content_parent = 0, $link = false, $actve_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_content'];
		
		if ($content_parent == false) {
			
			$content_parent = intval ( 0 );
		
		}
		
		$sql = "SELECT * from $table where  content_parent=$content_parent and content_type='page'  ";
		
		$q = CI::model ( 'core' )->dbQuery ( $sql );
		
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
					
					$to_print = str_ireplace ( '{link}', page_link ( $item ['id'] ), $to_print );
					
					foreach ( $item as $item_k => $item_v ) {
						$to_print = str_ireplace ( '{' . $item_k . '}', $item_v, $to_print );
					
					}
					
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
		
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			return $cache_content;
		
		} else {
			
			$to_cache = $this->content_helpers_getCaregoriesUlTree ( $content_parent, $link, $actve_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name, $include_first, $content_type, $add_ids, $orderby, $only_with_content );
			
			CI::model ( 'core' )->cacheWriteAndEncode ( $to_cache, $function_cache_id, $cache_group );
			
			return $to_cache;
		
		}
	
	}
	
	/**`

	 * @desc Prints the selected categories as an <UL> tree, you might pass several options for more flexibility

	 * @param array

	 * @param boolean

	 * @author      Peter Ivanov

	 * @version 1.0

	 * @since Version 1.0

	 */
	
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
		
		$content_type = addslashes ( $content_type );
		
		if ($content_type == false) {
			
			if ($include_first == true) {
				
				$sql = "SELECT * from $table where id=$content_parent  and taxonomy_type='category'   $remove_ids_q $add_ids_q group by id order by {$orderby [0]}  {$orderby [1]}  ";
			
			} else {
				
				$sql = "SELECT * from $table where parent_id=$content_parent and taxonomy_type='category'   $remove_ids_q $add_ids_q group by id order by {$orderby [0]}  {$orderby [1]}   ";
			
			}
		
		} else {
			
			if ($include_first == true) {
				
				$sql = "SELECT * from $table where id=$content_parent and (taxonomy_content_type='$content_type' or taxonomy_content_type='inherit' )  $remove_ids_q $add_ids_q   group by id order by {$orderby [0]}  {$orderby [1]}  ";
			
			} else {
				
				$sql = "SELECT * from $table where parent_id=$content_parent and taxonomy_type='category' and (taxonomy_content_type='$content_type' or taxonomy_content_type='inherit' )   $remove_ids_q  $add_ids_q group by id order by {$orderby [0]}  {$orderby [1]}   ";
			
			}
		
		}
		
		if (empty ( $limit )) {
			$limit = array (0, 10 );
		}
		
		if (! empty ( $limit )) {
			
			$my_offset = $limit [1] - $limit [0];
			
			$my_limit_q = " limit  {$limit[0]} , $my_offset  ";
		
		} else {
			
			$my_limit_q = false;
		
		}
		
		$children_of_the_main_parent = CI::model ( 'taxonomy' )->getItems ( $content_parent, $type = 'category_item', $visible_on_frontend, $limit );
		// 
		$q = CI::model ( 'core' )->dbQuery ( $sql, $cache_id = 'content_helpers_getCaregoriesUlTree_parent_cats_q_' . md5 ( $sql ), 'taxonomy/' . intval ( $content_parent ) );
		
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
						
						$content_ids_of_the_1_parent = CI::model ( 'taxonomy' )->getToTableIds ( $only_with_content [0], $limit );
						
						$content_ids_of_the_1_parent_o = $content_ids_of_the_1_parent;
						
						if (! empty ( $content_ids_of_the_1_parent )) {
							
							$only_with_content3 = array ();
							
							$chosen_categories_array = array ();
							
							foreach ( $only_with_content2 as $only_with_content2_i ) {
								
								$only_with_content2_i = str_ireplace ( '{id}', $item ['id'], $only_with_content2_i );
								
								$only_with_content2_i = intval ( $only_with_content2_i );
								
								$chosen_categories_array [] = $only_with_content2_i;
							
							}
							
							if (count ( $chosen_categories_array ) > 1) {
								
							//array_shift ( $chosen_categories_array );
							

							}
							
							$chosen_categories_array_i = implode ( ',', $chosen_categories_array );
							
							$children_of_the_next_parent = CI::model ( 'taxonomy' )->getToTableIds ( $only_with_content [0], $limit );
							
							$children_of_the_next_parent_i = implode ( ',', $children_of_the_next_parent );
							
							$children_of_the_next_parent_qty = count ( $chosen_categories_array );
							
							$q = " 
							select id , count(to_table_id) as qty from $table_taxonomy where
                             to_table_id in($children_of_the_next_parent_i) 
                             and parent_id  IN ($chosen_categories_array_i) 
                             and taxonomy_type =  'category_item'
                             group by to_table_id
                             $my_limit_q
                         ";
							
							//  p ( $q );
							

							$q = CI::model ( 'core' )->dbQuery ( $q, __FUNCTION__ . md5 ( $q ), 'taxonomy/global' );
							$total_count_array = array ();
							
							if (! empty ( $q )) {
								
								foreach ( $q as $q1 ) {
									$qty = intval ( $q1 ['qty'] );
									
									if (($children_of_the_next_parent_qty) == $qty) {
										$total_count_array [] = $q1;
									
									}
								
								}
							
							}
							
							//$q = intval ( $q [0] ['qty'] );
							

							$results_count = count ( $total_count_array );
							
							/*  $posts_data = array ();

                            $posts_data ['selected_categories'] = $chosen_categories_array;

                            $posts_data ['strict_category_selection'] = true;

                            if ($visible_on_frontend == true) {

                                $posts_data ['visible_on_frontend'] = 'y';

                            }

                            $results_count = 1;

                            $posts_data = $this->getContentAndCache($posts_data, $orderby = false, $limit = array(0,1), $count_only = false, $short_data = true, $only_fields = false) ;

                            */
							
							$content_ids_of_the_1_parent_i = implode ( ',', $children_of_the_main_parent );
							
							$chosen_categories_array_i = implode ( ',', $chosen_categories_array );
							
							/*  $q = " select  count(*) as qty from $table_taxonomy where

                    to_table= 'table_content'

                    and to_table_id in($content_ids_of_the_1_parent_i)

                    and content_type = 'post'

                    and parent_id  IN ($chosen_categories_array_i)

                    and taxonomy_type = 'category_item'

             



                    ;";*/
							
							//       print $q ; 
							

							/*

                            $q = " select  to_table_id,  count(parent_id) as parent_id123  from $table_taxonomy where

                    to_table= 'table_content'

                

                    and content_type = 'post'

                    and parent_id  IN ($chosen_categories_array_i)

                    and taxonomy_type = 'category_item'

             group by to_table_id



                    ;";

                            //$q = CI::model('core')->dbQuery ( $q, md5 ( $q ), 'taxonomy' );

                            $qty123 = array();

                            if (! empty ( $q )) {

                                //$q = intval ( $q [0] ['qty'] );

                         

                            //  $results_count = $q;

                            foreach($q as $q1){

                                if(intval($q1['parent_id123']) == count($chosen_categories_array) ){
 

                                //$results_count = 1;

                                //$qty123[] = $q1;

                                }                               

                            } 

                                

                                

                            } else {

                                $results_count = 0;

                            }*/
							
							//  print "<hr>";
							

							/*  if ($q == count ( $categories )) {

                        $strict_ids [] = $id;

                    } else {

                        $strict_ids [] = 'No such id';

                    }

                 */
							
							//  $posts_data = $this->contentGetByParams ( $posts_data );
							

							/*if (! empty ( $posts_data ['posts'] )) {

                                $results_count = count ( $posts_data ['posts'] );

                                //print $results_count;

                            



                            } else {

                                $results_count = 0;

                            }*/
							
							$result [$i] ['content_count'] = $results_count;
							
							$item ['content_count'] = $results_count;
							
						/*$only_with_content3 [] = $item ['id'];

                            

                            $only_with_content3 = array_unique ( $only_with_content3 );

                            $only_with_content3_i = implode ( ',', $only_with_content3 );

                            $content_ids_of_the_main_parent_i = implode ( ',', $content_ids_of_the_1_parent );

                            

                            if ($visible_on_frontend == true) {

                                $visible_on_frontend_q = " and to_table_id in (select id from $table_content where visible_on_frontend='y') ";

                            

                            }

                            

                            $q_1 = "select count(*) as qty from $table where

                        content_type = 'post' 

                        and parent_id in ({$item ['id']}) 

                         and to_table_id in ($content_ids_of_the_main_parent_i) 

                         

                        $visible_on_frontend_q

                         

                         

                        and to_table_id is not null

                          group by to_table_id

                        ";

                            

                            $q_1 = CI::model('core')->dbQuery ( $q_1, $cache_id = basename ( __FILE__, '.php' ) . __LINE__ . md5 ( $q_1 ), $cache_group = 'taxonomy' );

                            

                            $results_count = intval ( $q_1 [0] ['qty'] );*/
						
						} else {
							
							$results_count = 0;
						
						}
						
						$result [$i] ['content_count'] = $results_count;
						
						$item ['content_count'] = $results_count;
					
					} else {
						
						$do_not_show = false;
					
					}
				
				}
				
				$i ++;
			
			}
			
			if ($content_parent == 2163) {
			
			}
			
			if ($do_not_show == false) {
				
				$print1 = false;
				
				if ($ul_class_name == false) {
					
					$print1 = "<ul  class='category_tree'>";
				
				} else {
					
					$print1 = "<ul class='$ul_class_name'>";
				
				}
				
				print $print1;
				//	print($type);
				foreach ( $result as $item ) {
					
					if ($only_with_content == true) {
						
						$do_not_show = false;
						
						$check_in_table_content = false;
						$childern_content = array ();
						//	$childern_content = CI::model('taxonomy')->getItems ( $item ['id'], $type = 'category_item', $visible_on_frontend, $limit );
						

						//$childern_content2 = " SELECT  "
						

						$do_not_show = false;
						//	print($type);
						

						if (! empty ( $childern_content )) {
							
							$do_not_show = false;
							
						/*if (! empty ( $chosen_categories_array )) {

                            

                            $posts_data = array ();

                            $posts_data ['selected_categories'] = $chosen_categories_array;

                            $posts_data ['strict_category_selection'] = true;

                            if ($visible_on_frontend == true) {

                                $posts_data ['visible_on_frontend'] = 'y';

                            }

                            //$posts_data = $this->contentGetByParams ( $posts_data );
 
                            



                            //$posts_data = $this->getContentAndCache($posts_data, $orderby = false, $limit = array(0,1), $count_only = false, $short_data = true, $only_fields = false) ;

                            $do_not_show = false;



                      

                            if (! empty ( $posts_data ['posts'] )) {

                                //$results_count = count ( $posts_data ['posts'] );

                            //$do_not_show = false;

                            } else {

                                //$do_not_show = true;

                            }

                        }*/
						
						} else {
							
							$do_not_show = true;
						
						}
					
					} else {
						
						$do_not_show = false;
					
					}
					
					if ($do_not_show == false) {
						
						$output = $output . $item ['taxonomy_value'];
						
						if ($li_class_name == false) {
							
							print "<li class='category_element' id='category_item_{$item['id']}'>";
						
						} else {
							
							print "<li class='$li_class_name' id='category_item_{$item['id']}' >";
						
						}
					
					}
					
					if ($do_not_show == false) {
						
						if ($link != false) {
							
							$to_print = false;
							
							$to_print = str_ireplace ( '{id}', $item ['id'], $link );
							
							$to_print = str_ireplace ( '{taxonomy_url}', CI::model ( 'taxonomy' )->getUrlForIdAndCache ( $item ['id'] ), $to_print );
							
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
						
						} else {
							
							print $item ['taxonomy_value'];
						
						}
						
						//$content_parent, $link = false, $actve_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false, $content_type = false, $li_class_name = false) {
						

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
		
		$get = CI::model ( 'core' )->getDbData ( $table, $data, $limit = false, $offset = false, $orderby, $cache_group = 'menus', false );
		
		$get = $get [0];
		
		if (! empty ( $get )) {
			
			return true;
		
		} else {
			
			return false;
		
		}
	
	}
	
	function content_pingServersWithNewContent() {
		
		if ($_SERVER ["SERVER_NAME"] == 'localhost') {
			return false;
		}
		
		if ($_SERVER ["SERVER_NAME"] == '127.0.0.1') {
			return false;
		}
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_content'];
		
		$q = " SELECT id, content_title from  $table where is_pinged='n' or is_pinged IS NULL limit 0,1 ";
		
		$q = CI::model ( 'core' )->dbQuery ( $q );
		
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
				
				foreach ( $q as $the_post ) {
					
					$q2 = " UPDATE  $table set is_pinged='y' where id='{$the_post['id']}' ";
					
					$q2 = CI::model ( 'core' )->dbQ ( $q2 );
					
					$url = $this->getContentURLByIdAndCache ( $the_post ['id'] );
					
					$pages = array ();
					
					$pages [] = $the_post ['content_title'];
					
					$pages [] = $url;
					
					$line = trim ( $line );
					
					//print "Pinging $line \n\r";
					

					//$this->xmlrpc->server('http://rpc.pingomatic.com/', 80);
					

					@$this->xmlrpc->server ( $line, 80 );
					
					@$this->xmlrpc->method ( 'weblogUpdates.ping' );
					
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
		$cache_group = 'media/table_content/' . $content_id;
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			if ($cache_content == 'false') {
				
				return false;
			
			} else {
				
				return $cache_content;
			
			}
		
		}
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_media'];
		
		$q = "SELECT id FROM $table WHERE to_table = 'table_content'

        AND to_table_id = '$content_id'

        AND media_type = '$media_type'





        AND ID is not null ORDER BY media_order ASC";
		
		//($q);
		

		$q = CI::model ( 'core' )->dbQuery ( $q, __FUNCTION__ . md5 ( $q ), $cache_group );
		
		$ids = CI::model ( 'core' )->dbExtractIdsFromArray ( $q );
		
		if (! empty ( $ids )) {
			
			$media = CI::model ( 'core' )->mediaGet2 ( false, false, $ids );
			
			CI::model ( 'core' )->cacheWriteAndEncode ( $media, $function_cache_id, $cache_group );
			
			//$media = CI::model('core')->mediaGet ( $to_table = 'table_content', $content_id, $media_type = false, $order = "ASC", $queue_id = false, $no_cache = false );
			

			return $media;
			
		//$this->template ['videos'] = $media2 ['videos'];
		

		} else {
			
			CI::model ( 'core' )->cacheWriteAndEncode ( 'false', $function_cache_id, $cache_group );
		
		}
	
	}
	
	function mediaGetIdsForContentId($content_id, $media_type = false) {
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		$content_id = intval ( $content_id );
		$cache_group = 'media/table_content/' . $content_id;
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_content = CI::model ( 'core' )->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			if ($cache_content == 'false') {
				
				return false;
			
			} else {
				
				return $cache_content;
			
			}
		
		}
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_media'];
		
		$q = "SELECT id FROM $table WHERE to_table = 'table_content'

        AND to_table_id = '$content_id'

        AND media_type = '$media_type'





        AND ID is not null ORDER BY media_order ASC";
		
		//($q);
		

		$q = CI::model ( 'core' )->dbQuery ( $q, __FUNCTION__ . md5 ( $q ), $cache_group );
		
		$ids = CI::model ( 'core' )->dbExtractIdsFromArray ( $q );
		
		if (! empty ( $ids )) {
			
			CI::model ( 'core' )->cacheWriteAndEncode ( $ids, $function_cache_id, $cache_group );
			
			return $ids;
		
		} else {
			
			CI::model ( 'core' )->cacheWriteAndEncode ( 'false', $function_cache_id, $cache_group );
		
		}
	
	}

}

?>
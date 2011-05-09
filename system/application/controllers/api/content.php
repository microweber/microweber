<?php

class Content extends Controller {
	
	function __construct() {
		
		parent::Controller ();
		
		require_once (APPPATH . 'controllers/default_constructor.php');
		//p($user_session);
		require_once (APPPATH . 'controllers/api/default_constructor.php');
		
		if (CI::model ( 'users' )->is_logged_in () == false) {
			//    exit ( 'Login required' );
		}
	
	}
	
	//@todo this must be moved to a seperate voting api
	function vote() {
		@ob_clean ();
		if ($_POST) {
			$_POST ['to_table_id'] = CI::model ( 'core' )->securityDecryptString ( $_POST ['tt'] );
			$_POST ['to_table'] = CI::model ( 'core' )->securityDecryptString ( $_POST ['t'] );
			if (intval ( $_POST ['to_table_id'] ) == 0) {
				exit ( '1' );
			}
			
			if (($_POST ['to_table']) == '') {
				exit ( '2' );
			}
			
			$save = CI::model ( 'votes' )->votesCast ( $_POST ['to_table'], $_POST ['to_table_id'] );
			if ($save == true) {
				exit ( 'yes' );
			} else {
				exit ( 'no' );
			}
		} else {
			exit ( 'no votes casted!' );
		}
	
	}
	
	function save_taxonomy() {
		$id = user_id ();
		if ($id == 0) {
			exit ( 'Error: not logged in.' );
		}
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		
		if ($_POST) {
			
			p ( $_POST );
			exit ( 'TODO: not finished in file: ' . __FILE__ );
			exit ();
		}
	}
	
	function save_taxonomy_items_order() {
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		
		if ($_POST) {
			global $cms_db_tables;
			$table_taxonomy = $cms_db_tables ['table_taxonomy'];
			//p ( $_POST );
			parse_str ( $_POST ['items'], $itmes );
			//$itmes = unserialize($itmes);
			//p($itmes);
			foreach ( $itmes as $k => $i ) {
				
				//p($i);
				if (! empty ( $i )) {
					foreach ( $i as $ik => $iv ) {
						$updated_on = date ( "Y-m-d H:i:s" );
						
						$data_to_save = array ();
						$data_to_save ['id'] = $ik;
						if (($iv == 'root') or intval ( $iv ) == 0) {
							$iv = 0;
						}
						$iv = intval ( $iv );
						
						$item_save = array ();
						$item_save ['id'] = $ik;
						$item_save ['parent_id'] = $iv;
						
						$q = "update $table_taxonomy set parent_id='{$item_save ['parent_id']}'
,  updated_on='{$updated_on}'
						where id ='{$item_save ['id']}' ";
						//p($q);
						$q = CI::model ( 'core' )->dbQ ( $q );
						
					//p ( $data_to_save );
					}
				}
				
			//saveMenuItem
			

			// 
			

			//print $k.' - '.$i;
			

			}
			CI::model ( 'core' )->cleanCacheGroup ( 'taxonomy' );
			//	CI::model ( 'content' )->fixMenusPositions ( $_POST ['menu_id'] );
		

		}
	}
	
	function get_layout_config() {
		if ($_POST ['filename']) {
			$file = CI::model ( 'template' )->layoutGetConfig ( $_POST ['filename'] );
			$file = json_encode ( $file );
			print $file;
			exit ();
		}
	
	}
	
	function delete_taxonomy() {
		$id = user_id ();
		if ($id == 0) {
			exit ( 'Error: not logged in.' );
		}
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		
		if ($_POST ['id']) {
			$del_id = $_POST ['id'];
		}
		
		if (url_param ( 'id' )) {
			$del_id = url_param ( 'id' );
		}
		
		if ($del_id != 0) {
			CI::model ( 'taxonomy' )->taxonomyDelete ( $del_id );
		
		}
	}
	
	function save_post() {
		$id = user_id ();
		if ($id == 0) {
			exit ( 'Error: not logged in.' );
		}
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		
		if ($_POST) {
			$save = post_save ( $_POST );
			$save = json_encode ( $save );
			print $save;
			exit ();
		}
	}
	
	function save_block() {
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		
		if ($_POST) {
			
			if ($_POST ['post_id']) {
				$_POST ['id'] = $_POST ['post_id'];
			}
			
			if ($_POST ['id']) {
				
				if ($_POST ['rel'] == 'global') {
					$_POST ['page_id'] = false;
				}
				
				if (($_POST ['rel']) == 'page') {
					//	p ( $_SERVER );
					$ref_page = $_SERVER ['HTTP_REFERER'];
					
					if ($ref_page != '') {
						
						$test = get_ref_page ();
						if (! empty ( $test )) {
							if ($_POST ['page_id'] == false) {
								$_POST ['page_id'] = $test ['id'];
							}
						}
						
					//$_POST ['page_id'] = $page_id;
					}
				}
				CI::model ( 'core' )->cleanCacheGroup ( 'global/blocks' );
				CI::model ( 'core' )->cleanCacheGroup ( 'options' );
				CI::model ( 'core' )->cleanCacheGroup ( 'custom_fields' );
				
				CI::model ( 'template' )->saveEditBlock ( $_POST ['id'], $_POST ['html'], $_POST ['page_id'] );
			}
		
		}
	}
	
	function save_option() {
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		
		if ($_POST) {
			
			if ($_POST ['option_key'] and $_POST ['option_group']) {
				
				CI::model ( 'core' )->optionsDeleteByKey ( $_POST ['option_key'], $_POST ['option_group'] );
			}
			if (strval ( $_POST ['option_key'] ) != '') {
				CI::model ( 'core' )->optionsSave ( $_POST );
			}
		
		}
	}
	
	function clean_word() {
		if ($_POST ['html']) {
			$html_to_save = clean_word ( $_POST ['html'] );
			exit ( $html_to_save );
		}
	}
	
	function delete_menu_item() {
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		
		if ($_POST) {
			$data_to_save = array ();
			if (isset ( $_POST ['id'] )) {
				$data_to_save = $_POST;
				
				$data_to_save = CI::model ( 'content' )->deleteMenuItem ( $_POST ['id'] );
				
				$a = json_encode ( $_POST );
				print $a;
				//p($data_to_save);
				/*if ($to_save == true) {
					$data_to_save ['item_type'] = 'menu_item';
					$data_to_save = CI::model ( 'content' )->saveMenuItem ( $data_to_save );
					
					CI::model ( 'core' )->cleanCacheGroup ( 'menus' );
					CI::model ( 'content' )->fixMenusPositions ( $_POST ['menu_id'] );
					print ($data_to_save) ;
				}*/
				exit ();
			}
		
		}
	
	}
	
	function save_menu_items() {
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		
		if ($_POST) {
			$data_to_save = array ();
			if (isset ( $_POST ['id'] )) {
				$data_to_save = $_POST;
				if (($_POST ['item_parent'] == 'root') or intval ( $_POST ['item_parent'] ) == 0) {
					$data_to_save ['item_parent'] = $_POST ['menu_id'];
				}
				$to_save = true;
				if (intval ( $data_to_save ['id'] ) != 0) {
					if (intval ( $data_to_save ['item_parent'] ) != intval ( $data_to_save ['id'] )) {
						$to_save = true;
					} else {
						$to_save = false;
					}
				
				}
				if ($to_save == true) {
					$data_to_save ['item_type'] = 'menu_item';
					$data_to_save = CI::model ( 'content' )->saveMenuItem ( $data_to_save );
					
					CI::model ( 'core' )->cleanCacheGroup ( 'menus' );
					CI::model ( 'content' )->fixMenusPositions ( $_POST ['menu_id'] );
					print ($data_to_save) ;
				}
				exit ();
			}
			
			if ($_POST ['reorder']) {
				
				//p ( $_POST );
				parse_str ( $_POST ['items'], $itmes );
				//$itmes = unserialize($itmes);
				//p($itmes);
				foreach ( $itmes as $k => $i ) {
					
					//p($i);
					if (! empty ( $i )) {
						foreach ( $i as $ik => $iv ) {
							$data_to_save = array ();
							$data_to_save ['id'] = $ik;
							if (($iv == 'root') or intval ( $iv ) == 0) {
								$iv = $_POST ['menu_id'];
							}
							$iv = intval ( $iv );
							$data_to_save ['item_parent'] = $iv;
							$data_to_save ['item_type'] = 'menu_item';
							$data_to_save = CI::model ( 'content' )->saveMenuItem ( $data_to_save );
							
						//p ( $data_to_save );
						}
					}
					
				//saveMenuItem
				

				// 
				

				//print $k.' - '.$i;
				

				}
				CI::model ( 'core' )->cleanCacheGroup ( 'menus' );
				CI::model ( 'content' )->fixMenusPositions ( $_POST ['menu_id'] );
			}
		}
	}
	
	/*function save_field() {
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		
		if ($_POST) {
			$save_global = false;
			if ($_POST ['attributes']) {
				//$_POST ['attributes'] = json_decode($_POST ['attributes']);
			//var_dump($_POST ['attributes']);
			}
			
			if (intval ( $_POST ['attributes'] ['page'] ) != 0) {
				$page_id = intval ( $_POST ['attributes'] ['page'] );
				$content_id = $page_id;
			}
			
			if (intval ( $_POST ['attributes'] ['post'] ) != 0) {
				$post_id = intval ( $_POST ['attributes'] ['post'] );
				$content_id = $post_id;
			}
			
			if (intval ( $_POST ['attributes'] ['category'] ) != 0) {
				$category_id = intval ( $_POST ['attributes'] ['category'] );
			}
			
			if (($_POST ['attributes'] ['global']) != false) {
				$save_global = true;
			}
			
			if (($_POST ['attributes'] ['rel']) == 'global') {
				$save_global = true;
			}
			if (($_POST ['attributes'] ['rel']) == 'post') {
				$ref_page = $_SERVER ['HTTP_REFERER'];
				$ref_page = $_SERVER ['HTTP_REFERER'] . '/json:true';
				$ref_page = file_get_contents ( $ref_page );
				if ($ref_page != '') {
					$save_global = false;
					$ref_page = json_decode ( $ref_page );
					$page_id = $ref_page->post->id;
				}
			}
			
			if (($_POST ['attributes'] ['rel']) == 'page') {
				//	p ( $_SERVER );
				$ref_page = $_SERVER ['HTTP_REFERER'];
				$ref_page = $_SERVER ['HTTP_REFERER'] . '/json:true';
				$ref_page = file_get_contents ( $ref_page );
				if ($ref_page != '') {
					$save_global = false;
					$ref_page = json_decode ( $ref_page );
					$page_id = $ref_page->page->id;
					$content_id = $page_id;
				}
			}
			
			if ($category_id == false and $page_id == false and $post_id == false and $save_global == false) {
				exit ( 'Error: plase specify integer value for at least one of those attributes - page, post or category' );
			}
			
			if (($_POST ['attributes'] ['field']) != '') {
				if (($_POST ['html']) != '') {
					$field = trim ( $_POST ['attributes'] ['field'] );
					
					$html_to_save = $_POST ['html'];
					$html_to_save = clean_word ( $html_to_save );
					
					if ($save_global == false) {
						if ($content_id) {
							
							if ($_POST ['attributes'] ['rel'] == 'page' or $_POST ['attributes'] ['rel'] == 'post') {
								
								$for_histroy = get_page ( $content_id );
								if (stristr ( $field, 'custom_field_' )) {
									$field123 = str_ireplace ( 'custom_field_', '', $field );
									$old = $for_histroy ['custom_fields'] [$field123];
								} else {
									$old = $for_histroy [$field];
								}
								
								$history_to_save = array ();
								$history_to_save ['table'] = 'table_content';
								$history_to_save ['id'] = $content_id;
								$history_to_save ['value'] = $old;
								$history_to_save ['field'] = $field;
								
								CI::model ( 'core' )->saveHistory ( $history_to_save );
							
							}
							
							$to_save = array ();
							$to_save ['id'] = $content_id;
							$to_save ['quick_save'] = true;
							$to_save [$field] = ($html_to_save);
							p($to_save); 
							$saved = CI::model ( 'content' )->saveContent ( $to_save );
							exit ( $saved );
						
						} else if ($category_id) {
							exit ( __FILE__ . __LINE__ . ' category is not implemented not rady yet' );
						
						}
					} else {
						
						$field_content = CI::model ( 'core' )->optionsGetByKey ( $_POST ['attributes'] ['field'], $return_full = true, $orderby = false );
						
						$to_save = $field_content;
						$to_save ['option_key'] = $_POST ['attributes'] ['field'];
						$to_save ['option_value'] = $html_to_save;
						$to_save ['option_key2'] = 'editable_region';
						
						$to_save = CI::model ( 'core' )->optionsSave ( $to_save );
						
						$history_to_save = array ();
						$history_to_save ['table'] = 'global';
					//	$history_to_save ['id'] = 'global';
						$history_to_save ['value'] = $field_content ['option_value'];
						$history_to_save ['field'] = $field;
						
						CI::model ( 'core' )->saveHistory ( $history_to_save );
						
						exit ( $to_save );
						
					//p ( $field_content );
					

					//optionsSave($data)
					}
				
				}
			
			} else {
				exit ( 'Error: plase specify a "field" attribute' );
			
			}
		
		}
	}*/
	function delete_custom_field_by_name() {
		$a = is_admin ();
		if ($a == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		
		if ($_POST) {
			$the_field_data_all = $_POST;
		} else {
			exit ( 'Error: no POST?' );
		}
		
		if ($_POST ['id']) {
			$id = intval ( $_POST ['id'] );
		}
		if ($_POST ['field_id']) {
			$id = intval ( $_POST ['field_id'] );
		}
		
		$content_id = $_POST ['content_id'];
		
		foreach ( $_POST as $k => $v ) {
			if (strstr ( $k, 'custom_field_' )) {
				$field = $k;
				$field = str_ireplace ( 'custom_field_', '', $field );
				$html_to_save = $v;
			}
		}
		
		if ($content_id) {
			
			$for_histroy = get_page ( $content_id );
			if (empty ( $for_histroy )) {
				$for_histroy = get_post ( $content_id );
			}
			
			if (stristr ( $field, 'custom_field_' )) {
				$field123 = str_ireplace ( 'custom_field_', '', $field );
				$old = $for_histroy ['custom_fields'] [$field123];
			} else {
				$old = $for_histroy [$field];
			}
			
			$history_to_save = array ();
			$history_to_save ['table'] = 'table_content';
			$history_to_save ['id'] = $content_id;
			$history_to_save ['value'] = $old;
			$history_to_save ['field'] = $field;
			//p ( $history_to_save );
			CI::model ( 'core' )->saveHistory ( $history_to_save );
			CI::model ( 'core' )->cleanCacheGroup ( 'content' . DIRECTORY_SEPARATOR . $content_id );
			
			global $cms_db_tables;
			$custom_field_table = $cms_db_tables ['table_custom_fields'];
			$custom_field_to_delete = array ();
			$custom_field_to_delete ['custom_field_name'] = $field;
			$custom_field_to_delete ['to_table'] = 'table_content';
			$custom_field_to_delete ['to_table_id'] = $content_id;
			p ( $custom_field_to_delete );
			$id = CI::model ( 'core' )->deleteData ( $custom_field_table, $custom_field_to_delete, 'custom_fields' );
			
			//$saved = CI::model ( 'core' )->deleteCustomFieldById ( $id );
			print ($id) ;
		}
	}
	
	function delete_custom_field_by_id() {
		$a = is_admin ();
		if ($a == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		
		if ($_POST) {
			$the_field_data_all = $_POST;
		} else {
			exit ( 'Error: no POST?' );
		}
		
		if ($_POST ['id']) {
			$id = intval ( $_POST ['id'] );
		}
		if ($_POST ['field_id']) {
			$id = intval ( $_POST ['field_id'] );
		}
		
		$content_id = $_POST ['content_id'];
		
		foreach ( $_POST as $k => $v ) {
			if (strstr ( $k, 'custom_field_' )) {
				$field = $k;
				$html_to_save = $v;
			}
		}
		
		if ($content_id) {
			
			$for_histroy = get_page ( $content_id );
			if (empty ( $for_histroy )) {
				$for_histroy = get_post ( $content_id );
			}
			
			if (stristr ( $field, 'custom_field_' )) {
				$field123 = str_ireplace ( 'custom_field_', '', $field );
				$old = $for_histroy ['custom_fields'] [$field123];
			} else {
				$old = $for_histroy [$field];
			}
			
			$history_to_save = array ();
			$history_to_save ['table'] = 'table_content';
			$history_to_save ['id'] = $content_id;
			$history_to_save ['value'] = $old;
			$history_to_save ['field'] = $field;
			//p ( $history_to_save );
			CI::model ( 'core' )->saveHistory ( $history_to_save );
			CI::model ( 'core' )->cleanCacheGroup ( 'content' . DIRECTORY_SEPARATOR . $content_id );
		
		}
		if ($id) {
			$saved = CI::model ( 'core' )->deleteCustomFieldById ( $id );
			print ($saved) ;
		}
	}
	
	/*
@todo: for categories also
*/
	function save_field_simple() {
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		
		if ($_POST) {
			$the_field_data_all = $_POST;
		} else {
			exit ( 'Error: no POST?' );
		}
		$content_id = $_POST ['content_id'];
		
		foreach ( $_POST as $k => $v ) {
			if (strstr ( $k, 'custom_field_' )) {
				$field = $k;
				$html_to_save = $v;
			}
		}
		
		if ($content_id) {
			
			$for_histroy = get_page ( $content_id );
			if (empty ( $for_histroy )) {
				$for_histroy = get_post ( $content_id );
			}
			
			if (stristr ( $field, 'custom_field_' )) {
				$field123 = str_ireplace ( 'custom_field_', '', $field );
				$old = $for_histroy ['custom_fields'] [$field123];
			} else {
				$old = $for_histroy [$field];
			}
			
			$history_to_save = array ();
			$history_to_save ['table'] = 'table_content';
			$history_to_save ['id'] = $content_id;
			$history_to_save ['value'] = $old;
			$history_to_save ['field'] = $field;
			//p ( $history_to_save );
			CI::model ( 'core' )->saveHistory ( $history_to_save );
		
		}
		
		$to_save = array ();
		$to_save ['id'] = $content_id;
		$to_save ['quick_save'] = true;
		$to_save [$field] = ($html_to_save);
		//print "<h2>For content $content_id</h2>";
		//p ( $to_save );
		$saved = CI::model ( 'content' )->saveContent ( $to_save );
		
		$html_to_save = CI::model ( 'template' )->parseMicrwoberTags ( $html_to_save, $options = false );
		
		print ($html_to_save) ;
		exit ();
	
	}
	
	function save_field() {
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		
		if ($_POST) {
			$the_field_data_all = $_POST;
		} else {
			exit ( 'Error: no POST?' );
		}
		
		$is_no_save = url_param ( 'peview', true );
		//p($is_no_save);
		

		$ref_page = $_SERVER ['HTTP_REFERER'];
		
		if ($ref_page != '') {
			
			//$page_id = $ref_page->page->id;
			//$the_ref_page = get_page ( $page_id );
			$ref_page = $the_ref_page = get_ref_page ();
			//p($ref_page);
			$page_id = $ref_page ['id'];
		
		}
		$json_print = array ();
		foreach ( $the_field_data_all as $the_field_data ) {
			
			if (! empty ( $the_field_data )) {
				
				$save_global = false;
				if ($the_field_data ['attributes']) {
					//$the_field_data ['attributes'] = json_decode($the_field_data ['attributes']);
				//var_dump($the_field_data ['attributes']);
				}
				
				if (intval ( $the_field_data ['attributes'] ['page'] ) != 0) {
					$page_id = intval ( $the_field_data ['attributes'] ['page'] );
					$content_id = $page_id;
					$the_ref_page = get_page ( $page_id );
				}
				
				if (intval ( $the_field_data ['attributes'] ['post'] ) != 0) {
					$post_id = intval ( $the_field_data ['attributes'] ['post'] );
					$content_id = $post_id;
					$the_ref_post = get_post ( $post_id );
				}
				
				if (intval ( $the_field_data ['attributes'] ['category'] ) != 0) {
					$category_id = intval ( $the_field_data ['attributes'] ['category'] );
				}
				$page_element_id = false;
				if (strval ( $the_field_data ['attributes'] ['id'] ) != '') {
					$page_element_id = ($the_field_data ['attributes'] ['id']);
				}
				
				if (($the_field_data ['attributes'] ['global']) != false) {
					$save_global = true;
				}
				
				if (($the_field_data ['attributes'] ['rel']) == 'global') {
					$save_global = true;
				}
				if (($the_field_data ['attributes'] ['rel']) == 'post') {
					
					if ($ref_page != '') {
						$save_global = false;
						$ref_post = $the_ref_post = get_ref_post ();
						//p ( $ref_post );
						$post_id = $ref_post ['id'];
						$page_id = $ref_page ['id'];
						$content_id = $post_id;
					}
				}
				
				if (($the_field_data ['attributes'] ['rel']) == 'page') {
					//	p ( $_SERVER );
					

					if ($ref_page != '') {
						$save_global = false;
						$ref_page = $the_ref_page = get_ref_page ();
						$page_id = $ref_page ['id'];
						
						$content_id = $page_id;
					}
				}
				
				if (($the_field_data ['attributes'] ['rel']) == 'PAGE_ID') {
					//	p ( $_SERVER );
					

					if ($ref_page != '') {
						$save_global = false;
						$ref_page = $the_ref_page = get_ref_page ();
						$page_id = $ref_page ['id'];
						
						$content_id = $page_id;
					}
				}
				
				if (($the_field_data ['attributes'] ['rel']) == 'POST_ID') {
					//	p ( $_SERVER );
					

					if ($ref_page != '') {
						$save_global = false;
						$ref_page = $the_ref_page = get_ref_page ();
						$page_id = $ref_page ['id'];
						
						$content_id = $page_id;
					}
				}
				
				if ($category_id == false and $page_id == false and $post_id == false and $save_global == false) {
					print ('Error: plase specify integer value for at least one of those attributes - page, post or category') ;
				} else {
					$some_mods = array ();
					if (($the_field_data ['attributes'] ['field']) != '') {
						if (($the_field_data ['html']) != '') {
							$field = trim ( $the_field_data ['attributes'] ['field'] );
							
							$html_to_save = $the_field_data ['html'];
							$html_to_save = str_replace ( 'MICROWEBER', 'microweber', $html_to_save );
							//$html_to_save = str_replace ( '<div><br></div>', '<br>', $html_to_save );
							//$html_to_save = str_replace ( '<div><br /></div>', '<br />', $html_to_save );
							//$html_to_save = str_replace ( '<div></div>', '<br />', $html_to_save );
							

							$content = $html_to_save;
							$html_to_save = $content;
							if (strstr ( $content, 'mw_params_encoded' ) == true) {
								
								$tags1 = extract_tags ( $content, 'div', $selfclosing = true, $return_the_entire_tag = true, $charset = 'UTF-8' );
								//	p($tags);
								$matches = $tags1;
								if (! empty ( $matches )) {
									//
									foreach ( $matches as $m ) {
										
										//
										

										if ($m ['tag_name'] == 'div') {
											
											$attr = $m ['attributes'];
											if (strval ( $attr ['module_id'] ) == '') {
												$attr ['module_id'] = 'module_' . rand () . rand () . rand () . rand ();
											}
											
											if ($attr ['module_id'] != '') {
												$mw_params_encoded = $attr;
												$mod_id = $attr ['module_id'];
												$tag1 = "<microweber ";
												
												foreach ( $mw_params_encoded as $k => $v ) {
													$skip_key = false;
													if ($k == 'edit') {
														$v = 'edit_tag';
													}
													if ($k == 'onmouseup') {
														$v = '';
														$skip_key = true;
													}
													if ($k == 'class') {
														$v = '';
														$skip_key = true;
													}
													if ($skip_key == false) {
														if ((strtolower ( trim ( $k ) ) != 'save') and (strtolower ( trim ( $k ) ) != 'submit')) {
															$tag1 = $tag1 . "{$k}=\"{$v}\" ";
														}
													}
													$tag1 = $tag1 . "module=\"{$attr ['mw_params_module']}\" ";
												
												}
												$tag1 .= " />";
												$some_mods [$mod_id] = $tag1;
											}
										}
									}
								}
								
							/*$doc = new DOMDocument ();
								$doc->preserveWhiteSpace = true;
								$doc->loadHTML ( $content );
								$xpath = new DOMXpath ( $doc );
								foreach ( $xpath->query ( '//div[@mw_params_encoded]' ) as $a ) {
									//	$clunker_vars = get_object_vars($a->nodeValue); // we pass the object, not th
									//echo "Found {$a->previousSibling->previousSibling->nodeValue}," . " by {$a->previousSibling->nodeValue}\n";
									

									$mw_params_encoded = $a->getAttribute ( 'mw_params_encoded' );
									$mod_id = $a->getAttribute ( 'module_id' );
									
									$mw_params_encoded = base64_decode ( $mw_params_encoded );
									$mw_params_encoded = unserialize ( $mw_params_encoded );
									
									//$c1 = $a->nodeValue;
									$c1 = domNodeContent ( $a, 1 );
									
									if (! empty ( $mw_params_encoded )) {
										$tag1 = "<microweber ";
										
										foreach ( $mw_params_encoded as $k => $v ) {
											if ($k == 'edit') {
												$v = 'edit_tag';
											}
											if ((strtolower ( trim ( $k ) ) != 'save') and (strtolower ( trim ( $k ) ) != 'submit')) {
												$tag1 = $tag1 . "{$k}=\"{$v}\" ";
											}
										
										}
										$tag1 .= " />";
										$some_mods [$mod_id] = $tag1;
										//p ( $tag1 );
									//	p ( $c1 );
									//$c1 = trim ( $c1 );
									//$content = trim ( $content );
									//$content123 = explode ( $c1, $content );
									//$content = str_ireplace ( $c1, $tag1, $content );
									// p($content123);
									}
								}*/
							//
							//$parsed = get_string_between ( $content, "mw_params_encoded=\"", '"' );
							//
							

							}
							//p($some_mods,1);
							$html_to_save = $content;
							
							foreach ( $some_mods as $some_mod_k => $some_mod_v ) {
								
								//$t1 = extact_tag_by_attr ( 'module_id', $some_mod_k, $content, 'div' );
								//p ( $t1 );
								

								$content = preg_replace ( "#<div[^>]*id=\"{$some_mod_k}\".*?</div>#si", $some_mod_v, $content );
							
							}
							
							if ($is_no_save != true) {
								$pattern = "/mw_last_hover=\"[0-9]*\"/";
								$pattern = "/mw_last_hover=\"[0-9]*\"/i";
								
								$content = preg_replace ( $pattern, "", $content );
								
								$pattern = "/mw_last_hover=\"\"/";
								$content = preg_replace ( $pattern, "", $content );
								
								$pattern = "/mw_tag_edit=\"[0-9]*\"/i";
								
								$content = preg_replace ( $pattern, "", $content );
								
								$pattern = "/mw_tag_edit=\"\"/";
								$content = preg_replace ( $pattern, "", $content );
							}
							//$content = preg_replace ( "#<div[^>]*id=\"{$some_mod_k}\".*?</div>#si", $some_mod_v, $content );
							

							$html_to_save = $content;
							//	p ( $content,1 );
							if (strstr ( $content, '<div' ) == true) {
								
								$relations = array ();
								$tags = extract_tags ( $content, 'div', $selfclosing = false, $return_the_entire_tag = true, $charset = 'UTF-8' );
								
								$matches = $tags;
								if (! empty ( $matches )) {
									//
									foreach ( $matches as $m ) {
										
										//
										

										//	p ( ($m) );
										if ($m ['tag_name'] == 'div') {
											$replaced = false;
											$attr = $m ['attributes'];
											
											if ($attr ['mw_params_encoded']) {
												$decode_params = $attr ['mw_params_encoded'];
												//$decode_params = base64_decode ( $decode_params );
												$decode_params = 'edit_tag';
												//p ( $decode_params );
											

											//p ( $attr, 1 );
											//print 1111111111111111111111111111111111111111111111111111111;
											}
											foreach ( $some_mods as $some_mod_k => $some_mod_v ) {
												//p(($m));
												//	p($some_mod_v);
												

												if (stristr ( $content, $some_mod_k )) {
													//p ( $content );
												//$content = str_ireplace ( $m ['full_tag'], $some_mod_v, $content );
												//p ( $content, 1 );
												}
											}
											//p ( $content, 1 );
											

											if ($attr ['class'] == 'module') {
												//	p($attr);
												if ($attr ['base64_array'] != '') {
													
													$base64_array = base64_decode ( $attr ['base64_array'] );
													$base64_array = unserialize ( $base64_array );
													if (! empty ( $base64_array )) {
														$tag1 = "<microweber ";
														
														foreach ( $base64_array as $k => $v ) {
															if ((strtolower ( trim ( $k ) ) != 'save') and (strtolower ( trim ( $k ) ) != 'submit')) {
																$tag1 = $tag1 . "{$k}=\"{$v}\" ";
															}
														}
														$tag1 .= " />";
														$to_save [] = $tag1;
														
														$content = str_ireplace ( $m ['full_tag'], $tag1, $content );
														$replaced = true;
														//p($base64_array);
													}
												}
												if ($replaced == false) {
													if ($attr ['edit'] != '') {
														$tag = ($attr ['edit']);
														$tag = 'edit_tag';
														//$tag = base64_decode ( $tag );
														//p ( $tag );
														

														if (strstr ( $tag, 'module_id=' ) == false) {
															
															$tag = str_replace ( '/>', ' module_id="module_' . date ( 'Ymdhis' ) . rand () . '" />', $tag );
														
														}
														
														$to_save [] = $tag;
														if ($tag != false) {
															$content = str_ireplace ( $m ['full_tag'], $tag, $content );
														}
													}
												}
											}
										
										}
									
									}
									$html_to_save = $content;
								}
							
							}
							$html_to_save = str_ireplace ( 'class="ui-droppable"', '', $html_to_save );
							$html_to_save = str_ireplace ( '<div><div></div><div><div></div>', '<br />', $html_to_save );
							//$html_to_save = str_ireplace ( 'class="ui-droppable"', '', $html_to_save );
							

							//$html_to_save =utfString( $html_to_save );
							//$html_to_save = htmlspecialchars ( $html_to_save, ENT_QUOTES );
							//$html_to_save = html_entity_decode ( $html_to_save );
							//p($html_to_save);
							//	p($content,1);
							$html_to_save = clean_word ( $html_to_save );
							
							
							if ($save_global == false) {
								
								if ($content_id) {
									
									if ($page_id) {
										
										$for_histroy = $the_ref_page;
										if ($post_id) {
											$for_histroy = $the_ref_post;
										}
										
										if (stristr ( $field, 'custom_field_' )) {
											$field123 = str_ireplace ( 'custom_field_', '', $field );
											$old = $for_histroy ['custom_fields'] [$field123];
										} else {
											$old = $for_histroy [$field];
										}
										
										$history_to_save = array ();
										$history_to_save ['table'] = 'table_content';
										$history_to_save ['id'] = $content_id;
										$history_to_save ['value'] = $old;
										$history_to_save ['field'] = $field;
										//p ( $history_to_save );
										if ($is_no_save != true) {
											CI::model ( 'core' )->saveHistory ( $history_to_save );
										}
									
									}
									
									
									$to_save = array ();
									$to_save ['id'] = $content_id;
									$to_save ['quick_save'] = true;
									
									$to_save ['page_element_id'] = $page_element_id;
									$to_save ['page_element_content'] = CI::model ( 'template' )->parseMicrwoberTags ( $html_to_save, $options = false );
									$to_save [$field] = ($html_to_save);
									//print "<h2>For content $content_id</h2>";
									//p ( $to_save );
									//p ( $html_to_save, 1 );
									$json_print [] = $to_save;
									if ($is_no_save != true) {
										$saved = CI::model ( 'content' )->saveContent ( $to_save );
									//	p($to_save);
									//p($content_id);
											//p($page_id);
						//	p ( $html_to_save ,1);
									}
									//print ($html_to_save) ;
									

									$html_to_save = CI::model ( 'template' )->parseMicrwoberTags ( $html_to_save, $options = false );
								
								} else if ($category_id) {
									print (__FILE__ . __LINE__ . ' category is not implemented not rady yet') ;
								
								}
							} else {
								
								$field_content = CI::model ( 'core' )->optionsGetByKey ( $the_field_data ['attributes'] ['field'], $return_full = true, $orderby = false );
								
								$to_save = $field_content;
								$to_save ['option_key'] = $the_field_data ['attributes'] ['field'];
								$to_save ['option_value'] = $html_to_save;
								$to_save ['option_key2'] = 'editable_region';
								$to_save ['page_element_id'] = $page_element_id;
								$to_save ['page_element_content'] = CI::model ( 'template' )->parseMicrwoberTags ( $html_to_save, $options = false );
								//print "<h2>Global</h2>"; 
								
								if ($is_no_save != true) {
									$to_save = CI::model ( 'core' )->optionsSave ( $to_save );
								}
								$json_print [] = $to_save;
								$history_to_save = array ();
								$history_to_save ['table'] = 'global';
								//	$history_to_save ['id'] = 'global';
								$history_to_save ['value'] = $field_content ['option_value'];
								$history_to_save ['field'] = $field;
								if ($is_no_save != true) {
									CI::model ( 'core' )->saveHistory ( $history_to_save );
								}
								$html_to_save = CI::model ( 'template' )->parseMicrwoberTags ( $html_to_save, $options = false );
								//	$json_print[] = array ($the_field_data ['attributes'] ['id'] => $html_to_save );
							

							//	print ($html_to_save) ;
							//	print ($to_save) ;
							

							//p ( $field_content );
							

							//optionsSave($data)
							}
						
						}
					
					} else {
						
					//	print ('Error: plase specify a "field" attribute') ;
					//	p($the_field_data);
					}
				}
			}
		
		}
		//	p($_POST);
		/*	if (! empty ( $_POST )) {
			foreach ( $_POST as $k => $v ) {
				
				//
				$v2 = array ();
				foreach ( $v as $v1_k => $v1_v ) {
					$html = CI::model ( 'template' )->parseMicrwoberTags ( $v1_v, $options = false );
					$v2 [$v1_k] =  ( $html );
				}
				
				$json_print [$k] = $v2;
			}
		}
		*/
		header ( 'Cache-Control: no-cache, must-revalidate' );
		header ( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
		header ( 'Content-type: application/json' );
		$json_print = json_encode ( $json_print );
		print $json_print;
		
		CI::model ( 'core' )->cleanCacheGroup ( 'global/blocks' );
		exit ();
	}
	
	function load_history_file() {
		
		if ((trim ( strval ( $_POST ['history_file'] ) ) != '') and strval ( $_POST ['history_file'] ) != 'false') {
			//	p ( $_POST );
			$id = is_admin ();
			if ($id == false) {
				exit ( 'Error: not logged in as admin.' );
			} else {
				$history_file = base64_decode ( $_POST ['history_file'] );
				//print $history_file;
				$history_file = $this->load->file ( $history_file, true );
				
				$history_file = CI::model ( 'template' )->parseMicrwoberTags ( $history_file );
				print $history_file;
				exit ();
				// 
			}
		}
	
	}
	
	function load_block() {
		
		if ($_POST) {
			if ($_POST ['id']) {
				
				if ($_POST ['rel'] == 'global') {
					$_POST ['page_id'] = false;
				}
				
				if ((trim ( strval ( $_POST ['history_file'] ) ) != '') and strval ( $_POST ['history_file'] ) != 'false') {
					//	p ( $_POST );
					$id = is_admin ();
					if ($id == false) {
						exit ( 'Error: not logged in as admin.' );
					} else {
						$history_file = base64_decode ( $_POST ['history_file'] );
						// 
					}
				} else {
					$history_file = false;
				}
				
				$block = CI::model ( 'template' )->loadEditBlock ( $_POST ['id'], $_POST ['page_id'], $history_file );
				
				exit ( $block );
			}
		
		}
	}
	
	function save_page() {
		$usr = user_id ();
		if ($usr == 0) {
			exit ( 'Error: not logged in.' );
		}
		$usr = is_admin ();
		if ($usr == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		
		if ($_POST) {
			
			if ($_POST ['page_id']) {
				$_POST ['id'] = $_POST ['page_id'];
			}
			
			$save = page_save ( $_POST );
			
			$save = json_encode ( $save );
			print $save;
			exit ();
		}
	}
	
	function get_page() {
		$id = user_id ();
		if ($id == 0) {
			exit ( 'Error: not logged in.' );
		}
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		
		if ($_POST ['id']) {
			$save = get_page ( $_POST ['id'] );
			$save ['url'] = site_url ( $save ['content_url'] );
			$save = json_encode ( $save );
			print $save;
			exit ();
		}
	}
	
	function delete() {
		$id = user_id ();
		if ($id == 0) {
			exit ( 'Error: not logged in.' );
		}
		
		$post_id = $_POST ['id'];
		if ($post_id) {
			$the_post = get_post ( $post_id );
			
			$is_adm = is_admin ();
			
			if (($the_post ['created_by'] == $id) or $is_adm == true) {
				
				//if($the_post['content_parent'])
				

				//p($the_post);
				

				CI::model ( 'content' )->deleteContent ( $post_id );
				exit ( 'yes' );
			} else {
				exit ( 'Error: you cant delete this post, because its not yours.' );
			}
		} else {
			exit ( 'Error: invalid post id' );
		}
	
	}
	
	function report() {
		@ob_clean ();
		if ($_POST) {
			$_POST ['to_table_id'] = CI::model ( 'core' )->securityDecryptString ( $_POST ['tt'] );
			$_POST ['to_table'] = CI::model ( 'core' )->securityDecryptString ( $_POST ['t'] );
			if (intval ( $_POST ['to_table_id'] ) == 0) {
				exit ( '1' );
			}
			
			if (($_POST ['to_table']) == '') {
				exit ( '2' );
			}
			
			$save = CI::model ( 'reports' )->report ( $_POST ['to_table'], $_POST ['to_table_id'] );
			if ($save == true) {
				exit ( 'yes' );
			} else {
				exit ( 'no' );
			}
		} else {
			exit ( 'nothing is reported!' );
		}
	
	}

}




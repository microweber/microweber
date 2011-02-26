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
			CI::model ( 'core' )->optionsSave ( $_POST );
		
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
		print ($saved) ;
	
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
		
		$ref_page = $_SERVER ['HTTP_REFERER'];
		
		if ($ref_page != '') {
			
			//$page_id = $ref_page->page->id;
			//$the_ref_page = get_page ( $page_id );
			$ref_page = $the_ref_page = get_ref_page ();
			//p($ref_page);
			$page_id = $ref_page ['id'];
		
		}
		
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
				
				if ($category_id == false and $page_id == false and $post_id == false and $save_global == false) {
					print ('Error: plase specify integer value for at least one of those attributes - page, post or category') ;
				} else {
					
					if (($the_field_data ['attributes'] ['field']) != '') {
						if (($the_field_data ['html']) != '') {
							$field = trim ( $the_field_data ['attributes'] ['field'] );
							
							$html_to_save = $the_field_data ['html'];
							//$html_to_save =utfString( $html_to_save );
							//$html_to_save = htmlspecialchars ( $html_to_save, ENT_QUOTES );
							//$html_to_save = html_entity_decode ( $html_to_save );
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
										CI::model ( 'core' )->saveHistory ( $history_to_save );
									
									}
									
									$to_save = array ();
									$to_save ['id'] = $content_id;
									$to_save ['quick_save'] = true;
									$to_save [$field] = ($html_to_save);
									//print "<h2>For content $content_id</h2>";
									//p ( $to_save );
									$saved = CI::model ( 'content' )->saveContent ( $to_save );
									print ($saved) ;
								
								} else if ($category_id) {
									print (__FILE__ . __LINE__ . ' category is not implemented not rady yet') ;
								
								}
							} else {
								
								$field_content = CI::model ( 'core' )->optionsGetByKey ( $the_field_data ['attributes'] ['field'], $return_full = true, $orderby = false );
								
								$to_save = $field_content;
								$to_save ['option_key'] = $the_field_data ['attributes'] ['field'];
								$to_save ['option_value'] = $html_to_save;
								$to_save ['option_key2'] = 'editable_region';
								
								//print "<h2>Global</h2>";
								//p ( $to_save );
								$to_save = CI::model ( 'core' )->optionsSave ( $to_save );
								
								$history_to_save = array ();
								$history_to_save ['table'] = 'global';
								//	$history_to_save ['id'] = 'global';
								$history_to_save ['value'] = $field_content ['option_value'];
								$history_to_save ['field'] = $field;
								
								CI::model ( 'core' )->saveHistory ( $history_to_save );
								
								print ($to_save) ;
								
							//p ( $field_content );
							

							//optionsSave($data)
							}
						
						}
					
					} else {
						print ('Error: plase specify a "field" attribute') ;
					
					}
				}
			}
		
		}
		CI::model ( 'core' )->cleanCacheGroup ( 'global/blocks' );
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
		$id = user_id ();
		if ($id == 0) {
			exit ( 'Error: not logged in.' );
		}
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		
		if ($_POST) {
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




<?php
function html2a($html) {
	if (! preg_match_all ( '
@
\<\s*?(\w+)((?:\b(?:\'[^\']*\'|"[^"]*"|[^\>])*)?)\>
((?:(?>[^\<]*)|(?R))*)
\<\/\s*?\\1(?:\b[^\>]*)?\>
|\<\s*(\w+)(\b(?:\'[^\']*\'|"[^"]*"|[^\>])*)?\/?\>
@uxis', $html = trim ( $html ), $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER ))
		return $html;
	$i = 0;
	$ret = array ();
	foreach ( $m as $set ) {
		if (strlen ( $val = trim ( substr ( $html, $i, $set [0] [1] - $i ) ) ))
			$ret [] = $val;
		$val = $set [1] [1] < 0 ? array ('tag' => strtolower ( $set [4] [0] ) ) : array ('tag' => strtolower ( $set [1] [0] ), 'val' => html2a ( $set [3] [0] ) );
		if (preg_match_all ( '
/(\w+)\s*(?:=\s*(?:"([^"]*)"|\'([^\']*)\'|(\w+)))?/usix
', isset ( $set [5] ) && $set [2] [1] < 0 ? $set [5] [0] : $set [2] [0], $attrs, PREG_SET_ORDER )) {
			foreach ( $attrs as $a ) {
				$val ['attr'] [$a [1]] = $a [count ( $a ) - 1];
			}
		}
		$ret [] = $val;
		$i = $set [0] [1] + strlen ( $set [0] [0] );
	}
	$l = strlen ( $html );
	if ($i < $l)
		if (strlen ( $val = trim ( substr ( $html, $i, $l - $i ) ) ))
			$ret [] = $val;
	return $ret;
}

//$a = html2a($html);
//now we will make some neat html out of it
//echo a2html($a);


function a2html($a, $in = "") {
	if (is_array ( $a )) {
		$s = "";
		foreach ( $a as $t )
			if (is_array ( $t )) {
				$attrs = "";
				if (isset ( $t ['attr'] ))
					foreach ( $t ['attr'] as $k => $v )
						$attrs .= " ${k}=" . (strpos ( $v, '"' ) !== false ? "'$v'" : "\"$v\"");
				$s .= $in . "<" . $t ['tag'] . $attrs . (isset ( $t ['val'] ) ? ">\n" . a2html ( $t ['val'], $in . "  " ) . $in . "</" . $t ['tag'] : "/") . ">\n";
			} else
				$s .= $in . $t . "\n";
	} else {
		$s = empty ( $a ) ? "" : $in . $a . "\n";
	}
	return $s;
}
function DOMinnerHTML($element) {
	$innerHTML = "";
	$children = $element->childNodes;
	foreach ( $children as $child ) {
		$tmp_dom = new DOMDocument ();
		$tmp_dom->appendChild ( $tmp_dom->importNode ( $child, true ) );
		$innerHTML .= trim ( $tmp_dom->saveHTML () );
	}
	return $innerHTML;
}
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
			
			//p ( $_POST );
			

			$save = CI::model ( 'taxonomy' )->taxonomySave ( $_POST, $preserve_cache = false );
			CI::model ( 'core' )->cleanCacheGroup ( 'taxonomy' );
			exit ( $save );
			//exit ( 'TODO: not finished in file: ' . __FILE__ );
		//exit ();
		}
	}
	
	function cf_reorder() {
		
		$id = user_id ();
		if ($id == 0) {
			exit ( 'Error: not logged in.' );
		}
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		global $cms_db_tables;
		$custom_field_table1 = $cms_db_tables ['table_custom_fields'];
		$custom_field_table2 = $cms_db_tables ['table_custom_fields_config'];
		foreach ( $_POST ['cf_id'] as $key => $value ) {
			$q1 = "UPDATE {$custom_field_table1}  SET field_order={$key}  WHERE id={$value}";
			//p($q1);
			$q1 = CI::model ( 'core' )->dbQ ( $q1 );
			
			$q1 = "UPDATE {$custom_field_table2}  SET field_order={$key}  WHERE id={$value}";
			//p($q1);
			$q1 = CI::model ( 'core' )->dbQ ( $q1 );
		
		}
		CI::model ( 'core' )->cleanCacheGroup ( 'custom_fields' );
		//p ( $_POST );
	}
	
	function save_cf() {
		$id = user_id ();
		if ($id == 0) {
			exit ( 'Error: not logged in.' );
		}
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		$fs = strval ( trim ( $_POST ['field_scope'] ) );
		if ($fs != '' and $_POST ['post_id']) {
			if ($fs == 'page') {
				//unset($_POST ['post_id']);
			}
		
		}
		
		if (trim ( $_POST ['param'] ) == '') {
			
			$string = $_POST ['name'];
			$string = string_cyr2lat ( $string );
			
			$string = preg_replace ( '/[^a-z0-9_ ]/i', '', $string );
			if (trim ( $string ) == '') {
				//$string = $_POST ['type'] . rand ();
			} else {
				
				//neat code :)  
				$strtolower = function_exists ( 'mb_strtolower' ) ? 'mb_strtolower' : 'strtolower';
				$string = $strtolower ( $string );
				
				$_POST ['param'] = $string;
			
			}
		
		}
		if ($_POST ['param'] == '') {
			$string = string_cyr2lat ( $_POST ['name'] );
			$_POST ['param'] = $_POST ['name'];
		}
		//p($_POST);
		//	if ($_POST ['param'] != '') {
		//p($_POST);
		

		$s = CI::model ( 'core' )->saveCustomFieldConfig ( $_POST );
		//}
		

		CI::model ( 'core' )->cleanCacheGroup ( 'custom_fields' );
		//	p($_POST);
		

		exit ( $s );
	}
	
	function delete_cf() {
		$id = user_id ();
		if ($id == 0) {
			exit ( 'Error: not logged in.' );
		}
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		global $cms_db_tables;
		$custom_field_table1 = $cms_db_tables ['table_custom_fields'];
		$custom_field_table2 = $cms_db_tables ['table_custom_fields_config'];
		
		if ($_POST ['post_id'] != false and $_POST ['param'] != false) 

		{
			$p_id = intval ( $_POST ['post_id'] );
			$p_id1 = ($_POST ['param']);
			$q1 = "DELETE from {$custom_field_table2}  WHERE (post_id={$p_id} or page_id={$p_id} ) and param='$p_id1'";
			//p($q1);
		//	$q1 = CI::model ( 'core' )->dbQ ( $q1 );
		}
		
		$s = CI::model ( 'core' )->deleteDataById ( 'table_custom_fields_config', $_POST ['id'], $delete_cache_group = false );
		CI::model ( 'core' )->cleanCacheGroup ( 'custom_fields' );
		exit ( $s );
	}
	
	function save_taxonomy_items_order() {
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		$ids = $_POST ['category_item'];
		if (empty ( $ids )) {
			$ids = $_POST ['category_items'];
		
		}
		if (empty ( $ids )) {
			exit ();
		}
		
		$ids_implode = implode ( ',', $ids );
		global $cms_db_tables;
		$table_taxonomy = $cms_db_tables ['table_taxonomy'];
		
		$q = " SELECT id, updated_on from $table_taxonomy where id IN ($ids_implode)  order by updated_on DESC  ";
		$q = CI::model ( 'core' )->dbQuery ( $q );
		$max_date = $q [0] ['updated_on'];
		$max_date_str = strtotime ( $max_date );
		$i = 1;
		foreach ( $ids as $id ) {
			//$max_date_str = $max_date_str - $i;
			//	$nw_date = date ( 'Y-m-d H:i:s', $max_date_str );
			

			$q = " UPDATE $table_taxonomy set position='$i' where id = '$id'    ";
			//var_dump($q);
			$q = CI::model ( 'core' )->dbQ ( $q );
			
			$i ++;
		}
		
		CI::model ( 'core' )->cacheDelete ( 'cache_group', 'taxonomy' );
		
		//var_dump($q);
		exit ();
	
	}
	
	function save_taxonomy_items_order2() {
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
			$file = CI::model ( 'template' )->layoutGetConfig ( $_POST ['filename'], $_POST ['template'] );
			$file = json_encode ( $file );
			print $file;
			exit ();
		}
	
	}
	
	function get_taxonomy() {
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
			$del_id = url_param ( 'id', true );
		}
		//p($del_id);
		if ($del_id != 0) {
			$getSingleItem = CI::model ( 'taxonomy' )->getSingleItem ( $del_id );
			//p($getSingleItem);
			if (! empty ( $getSingleItem )) {
				$getSingleItem = json_encode ( $getSingleItem );
				exit ( $getSingleItem );
			}
		
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
		//	exit ( 'Error: not logged in as admin.' );
		}
		
		if ($_POST) {
			$save = post_save ( $_POST );
			$j = array ();
			$j ['id'] = $save ['id'];
			$save = json_encode ( $j );
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
			if (intval ( $_POST ['id'] ) == 0) {
				if ($_POST ['option_key'] and $_POST ['option_group']) {
					
					CI::model ( 'core' )->optionsDeleteByKey ( $_POST ['option_key'], $_POST ['option_group'] );
				}
			}
			if (strval ( $_POST ['option_key'] ) != '') {
				CI::model ( 'core' )->optionsSave ( $_POST );
			}
		
		}
	}
	
	function posts_sort_by_date() {
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		$ids = $_POST ['post'];
		if (empty ( $ids )) {
			$ids = $_POST ['page_list_holder'];
		
		}
		if (empty ( $ids )) {
			exit ();
		}
		
		$ids_implode = implode ( ',', $ids );
		global $cms_db_tables;
		$table = $cms_db_tables ['table_content'];
		
		$q = " SELECT id, updated_on from $table where id IN ($ids_implode)  order by updated_on DESC  ";
		$q = CI::model ( 'core' )->dbQuery ( $q );
		$max_date = $q [0] ['updated_on'];
		$max_date_str = strtotime ( $max_date );
		$i = 1;
		foreach ( $ids as $id ) {
			$max_date_str = $max_date_str - $i;
			$nw_date = date ( 'Y-m-d H:i:s', $max_date_str );
			
			$q = " UPDATE $table set updated_on='$nw_date' where id = '$id'    ";
			//var_dump($q);
			$q = CI::model ( 'core' )->dbQ ( $q );
			
			$i ++;
		}
		
		CI::model ( 'core' )->cacheDelete ( 'cache_group', 'content' );
		
		//var_dump($q);
		exit ();
	
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
	
	function save_menu_items_order() {
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		$ids = $_POST ['menu_items'];
		if (empty ( $ids )) {
			$ids = $_POST ['menu_item'];
		
		}
		if (empty ( $ids )) {
			exit ();
		}
		
		$ids_implode = implode ( ',', $ids );
		global $cms_db_tables;
		$table_taxonomy = TABLE_PREFIX . 'menus';
		
		$i = 1;
		foreach ( $ids as $id ) {
			//$max_date_str = $max_date_str - $i;
			//	$nw_date = date ( 'Y-m-d H:i:s', $max_date_str );
			

			$q = " UPDATE $table_taxonomy set position='$i' where id = '$id'    ";
			//var_dump($q);
			$q = CI::model ( 'core' )->dbQ ( $q );
			
			$i ++;
		}
		
		CI::model ( 'core' )->cacheDelete ( 'cache_group', 'menus' );
		
		//var_dump($q);
		exit ();
	
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
						
						$position = 0;
						foreach ( $i as $ik => $iv ) {
							$data_to_save = array ();
							$data_to_save ['id'] = $ik;
							if (($iv == 'root') or intval ( $iv ) == 0) {
								$iv = $_POST ['menu_id'];
							}
							$iv = intval ( $iv );
							$data_to_save ['position'] = $position;
							$data_to_save ['item_parent'] = $iv;
							$data_to_save ['item_type'] = 'menu_item';
							$data_to_save = CI::model ( 'content' )->saveMenuItem ( $data_to_save );
							
							//
							$position ++;
							
							p ( $data_to_save );
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
			//p ( $custom_field_to_delete );
			$id = CI::model ( 'core' )->deleteData ( $custom_field_table, $custom_field_to_delete, 'custom_fields' );
			$saved = CI::model ( 'core' )->deleteCustomFieldById ( $id );
			
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
		//p($_SERVER);
		$id = is_admin ();
		$id = 1;
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		//	
		if ($_POST) {
			
			if ($_POST ['mw_preview_only']) {
				$is_no_save = true;
			
			}
			$is_no_save = false;
			$the_field_data_all = $_POST;
			unset ( $the_field_data_all ['mw_preview_only'] );
		} else {
			exit ( 'Error: no POST?' );
		}
		
		//$is_no_save = url_param ( 'peview', true );
		

		$ref_page = $_SERVER ['HTTP_REFERER'];
		
		if ($ref_page != '') {
			
			//$page_id = $ref_page->page->id;
			//$the_ref_page = get_page ( $page_id );
			$ref_page = $the_ref_page = get_ref_page ();
			//p($ref_page);
			$page_id = $ref_page ['id'];
		
		}
		
		require_once (LIBSPATH . "simplehtmldom/simple_html_dom.php");
		//	require_once (LIBSPATH . "htmlfixer.php");
		

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
							
							if ($is_no_save != true) {
								$pattern = "/mw_last_hover=\"[0-9]*\"/";
								$pattern = "/mw_last_hover=\"[0-9]*\"/i";
								
								$html_to_save = preg_replace ( $pattern, "", $html_to_save );
								
								$pattern = "/mw_last_hover=\"\"/";
								$html_to_save = preg_replace ( $pattern, "", $html_to_save );
								
								$pattern = "/mw_tag_edit=\"[0-9]*\"/i";
								
								$html_to_save = preg_replace ( $pattern, "", $html_to_save );
								
								$pattern = "/mw_tag_edit=\"\"/";
								$html_to_save = preg_replace ( $pattern, "", $html_to_save );
							}
							//							
							$html_to_save = str_replace ( '<DIV', '<div', $html_to_save );
							$html_to_save = str_replace ( '/DIV', '/div', $html_to_save );
							$html_to_save = str_replace ( '<P>', '<p>', $html_to_save );
							$html_to_save = str_replace ( '</P>', '</p>', $html_to_save );
							$html_to_save = str_replace ( 'ui-droppable-disabled', '', $html_to_save );
							$html_to_save = str_replace ( 'ui-state-disabled', '', $html_to_save );
							$html_to_save = str_replace ( 'ui-sortable', '', $html_to_save );
							$html_to_save = str_replace ( 'ui-resizable', '', $html_to_save );
							
							$html_to_save = str_replace ( 'module_draggable', '', $html_to_save );
							
							$html_to_save = str_replace ( 'mw_no_module_mask', '', $html_to_save );
							
							$html_to_save = str_ireplace ( '<span >', '<span>', $html_to_save );
							$html_to_save = str_replace ( '<SPAN >', '<span>', $html_to_save );
							
							$html_to_save = str_replace ( '<div><div><div><div>', '', $html_to_save );
							$html_to_save = str_replace ( '</div></div></div></div>', '', $html_to_save );
							
							$html_to_save = str_replace ( '<div class="mw_dropable_generated"></div>', '', $html_to_save );
							$html_to_save = str_replace ( '<div   class="mw_dropable_generated"></div>', '', $html_to_save );
							$html_to_save = str_replace ( '<div class="mw_dropable_generated container"></div>', '', $html_to_save );
							
							//	$mw123 = 'microweber module_id="module_'.rand().rand().rand().rand().'" ';
							

							$html_to_save = str_replace ( 'tag_to_remove_add_module_string', 'microweber', $html_to_save );
							$html_to_save = str_replace ( 'TAG_TO_REMOVE_ADD_MODULE_STRING', 'microweber', $html_to_save );
							
							$html_to_save = str_replace ( 'add_element_string', 'add_element_string', $html_to_save );
							$html_to_save = str_replace ( 'ADD_ELEMENT_STRING', 'add_element_string', $html_to_save );
							$html_to_save = str_replace ( 'Z-INDEX: 5000;', '', $html_to_save );
							$html_to_save = str_replace ( 'FILTER: alpha(opacity=100);', '', $html_to_save );
							$html_to_save = str_replace ( 'MARGIN-TOP: 0px;', '', $html_to_save );
							$html_to_save = str_replace ( 'ZOOM: 1', '', $html_to_save );
							$html_to_save = str_replace ( 'contenteditable="true"', '', $html_to_save );
							$html_to_save = str_replace ( 'contenteditable="false"', '', $html_to_save );
							$html_to_save = str_replace ( 'sizset=""', '', $html_to_save );
							$html_to_save = str_replace ( 'sizcache=""', '', $html_to_save );
							
							$html_to_save = str_replace ( 'sizcache sizset', '', $html_to_save );
							$html_to_save = str_replace ( '<p   >', ' <p>', $html_to_save );
							$html_to_save = str_replace ( '<p >', ' <p>', $html_to_save );
							
							//sizcache="14533" sizset="40"
							

							//	$html_to_save = preg_replace ( "#*sizcache=\"[^0-9]\"#", '', $html_to_save );
							

							//$html_to_save = str_replace ( 'Z-INDEX: 5000;', '', $html_to_save );
							

							//$html_to_save = str_replace ( '<div><br></div>', '<br>', $html_to_save );
							//$html_to_save = str_replace ( '<div><br /></div>', '<br />', $html_to_save );
							//$html_to_save = str_replace ( '<div></div>', '<br />', $html_to_save );
							//p ( $html_to_save );
							

							$relations = array ();
							$tags = extract_tags ( $html_to_save, 'microweber', $selfclosing = true, $return_the_entire_tag = true );
							
							//p ( $tags );
							$matches = $tags;
							if (! empty ( $matches )) {
								//
								foreach ( $matches as $m ) {
									$attr = $m ['attributes'];
									
									if ($attr ['element'] != '') {
										$is_file = normalize_path ( ELEMENTS_DIR . $attr ['element'] . '.php', false );
										//	p ( $is_file );
										if (is_file ( $is_file )) {
											//file_get_contents($is_file); 
											//$this->load->vars ( $this->template );
											$element_layout = $this->load->file ( $is_file, true );
											$element_layout = CI::model ( 'template' )->parseMicrwoberTags ( $element_layout, false );
											$html_to_save = str_replace ( $m ['full_tag'], $element_layout, $html_to_save );
										}
										//$html_to_save = str_replace ( $m ['full_tag'], '', $html_to_save );
									}
									
								//	p ( $m,1 );
								//element
								

								}
							
							}
							
							//p ( $html_to_save, 1 );
							$content = $html_to_save;
							$html_to_save = $content;
							//if (strstr ( $content, 'mw_params_encoded' ) == true) {
							$content = str_replace ( '<span >', '<span>', $content );
							
							//$tags2 = html2a($content);
							//$tags1 = extract_tags ( $content, 'div', $selfclosing = false, $return_the_entire_tag = true );
							//p($tags1); 
							// p($tags1); 	
							$html = str_get_html ( $content );
							foreach ( $html->find ( 'div[mw_params_encoded="edit_tag"]' ) as $checkbox ) {
								//var_Dump($checkbox);
								$re1 = $checkbox->module_id;
								$style = $checkbox->style;
								$re2 = $checkbox->mw_params_module;
								$tag1 = "<microweber ";
								$tag1 = $tag1 . "module=\"{$re2}\" ";
								$tag1 = $tag1 . "module_id=\"{$re1}\" ";
								$tag1 = $tag1 . "style=\"{$style}\" ";
								$tag1 .= " />";
								//p($tag1);
								$checkbox->outertext = $tag1;
								$html->save ();
							
							}
							//$html = $html->save ();
							//							$content = $html->save ();
							//							$html = str_get_html ( $content );
							//							foreach ( $html->find ( 'span' ) as $checkbox ) {
							//								//var_Dump($checkbox);
							//								$style = $checkbox->style;
							//								$class = $checkbox->class;
							//								
							//								if (trim ( $style ) == '' and trim ( $class ) == '') {
							//									//var_Dump($style);
							//									//var_Dump($class);
							//									//var_Dump($in);
							//									$in = $checkbox->innertext;
							//									$checkbox->outertext = $in;
							//								}
							//								
							//								foreach ( $checkbox->find ( 'span' ) as $sp ) {
							//									$style = $sp->style;
							//									$class = $sp->class;
							//									
							//									if (trim ( $style ) == '' and trim ( $class ) == '') {
							//										//var_Dump($style);
							//										//var_Dump($class);
							//										//var_Dump($in);
							//									//	$in = $sp->innertext;
							//										//$sp->outertext = $in;
							//									}
							//								}
							//							
							//							}
							//							$content = $html->save ();
							//							
							//							// clean up memory
							//							$html->clear ();
							//							unset ( $html );
							//p($content);
							

							/*$matches = $tags1;
							if (! empty ( $matches )) {
								//
								foreach ( $matches as $m ) {
									
									//
									

									//p($m);
									

									//	p($m);
									if ($m ['tag_name'] == 'div') {
										$attr = $m ['attributes'];
										if ($attr ['edit'] == 'edit_tag') {
											
											if (strval ( $attr ['module_id'] ) == '') {
												$attr ['module_id'] = 'module_' . rand () . rand () . rand () . rand ();
											}
										}
										//p($attr);
										

										if ($attr ['module_id'] != '' and $attr ['mw_params_module'] != '') {
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
												if ($k == 'module') {
													$v = '';
													$skip_key = true;
												}
												if ($k == 'disabled') {
													$v = '';
													$skip_key = true;
												}
												if ($k == 'contenteditable') {
													$v = '';
													$skip_key = true;
												}
												if ($k == 'class') {
													//	$v = '';
												//	$skip_key = true;
												}
												if ($skip_key == false) {
													if ((strtolower ( trim ( $k ) ) != 'save') and (strtolower ( trim ( $k ) ) != 'submit')) {
														$tag1 = $tag1 . "{$k}=\"{$v}\" ";
													}
												}
											
											}
											$tag1 = $tag1 . "module=\"{$attr ['mw_params_module']}\" ";
											
											$tag1 .= " />";
											//$some_mods [$mod_id] = $tag1;
											//p($m);
											//print '---------find--------------'.htmlentities($m ['full_tag']);
											//print '-----------replace--------------'.htmlentities($tag1);
											//print '-----------////////////////////////////replace--------------';
											

											//p($m);
											$some_mods_1 = array ();
											$some_mods_1 ['find_tag'] = $m ['contents'];
											$some_mods_1 ['replace_tag'] = $tag1;
											$some_mods_1 ['rep_before'] = $content;
											//$content = str_replace_count ( $m ['full_tag'], $tag1, $content, 1 );
											$content = str_replace ( $m ['full_tag'], $tag1, $content );
											$some_mods_1 ['rep_after'] = $content;
											$some_mods [] = $some_mods_1;
										
										}
									}
								}
							}*/
							
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
							

							//}
							//	p($some_mods,1);
							$html_to_save = $html;
							//p ( $content );
							/*foreach ( $some_mods as $some_mod_k => $some_mod_v ) {
								
								$dom = new DOMDocument ();
								$dom->loadXML ( $content );
								$dom->preserveWhiteSpace = false;
								
								$domxpath = new DOMXPath ( $dom );
								
								$domTable = $domxpath->query ( "//div" . '[@' . 'module_id' . "='$some_mod_k']" );
								
								p ( $some_mod_k );
								
								//$domTable = $dom->getElementById ( $some_mod_k );
								p ( $domTable );
								foreach ( $domTable as $tables ) {
									print '---------------replace-------------------';
									print '---------------replace-------------------';
									$inner = DOMinnerHTML ( $tables );
									
									p ( htmlentities ( $inner ) );
									print '---------------replace-------------------';
									p ( htmlentities ( $some_mod_v ) );
									
									$content = str_replace ( $inner, $some_mod_v, $content );
									print '---------------replace-------------------';
									print '---------------replace-------------------';
								
								}
								
							//	$content = preg_replace ( "#<div[^>]*id=\"{$some_mod_k}\".*?</div>#si", $some_mod_v, $content );
							

							}*/
							//p ( $content );
							

							//$content = preg_replace ( "#<div[^>]*id=\"{$some_mod_k}\".*?</div>#si", $some_mod_v, $content );
							

							$html_to_save = $content;
							
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
															
														//	$tag = str_replace ( '/>', ' module_id="module_' . date ( 'Ymdhis' ) . rand () . '" />', $tag );
														

														}
														
														$to_save [] = $tag;
														if ($tag != false) {
															//$content = str_ireplace ( $m ['full_tag'], $tag, $content );
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
							//$html_to_save = str_ireplace ( '<div><div></div><div><div></div>', '<br />', $html_to_save );
							//$html_to_save = str_ireplace ( 'class="ui-droppable"', '', $html_to_save );
							$html_to_save = str_replace ( 'class="ui-sortable"', '', $html_to_save );
							//$html_to_save = str_replace ( '</microweber>', '', $html_to_save );
							

							//$html_to_save =utfString( $html_to_save );
							//$html_to_save = htmlspecialchars ( $html_to_save, ENT_QUOTES );
							//$html_to_save = html_entity_decode ( $html_to_save );
							//p($html_to_save);
							//	p($content,1);
							$html_to_save = clean_word ( $html_to_save );
							
							//$a = new HtmlFixer ();
							//$a->debug = true;
							//$html_to_save =  $a->getFixedHtml ( $html_to_save );
							

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
									// p($html_to_save,1);
									$to_save = array ();
									$to_save ['id'] = $content_id;
									$to_save ['quick_save'] = true;
									$to_save ['r'] = $some_mods;
									
									$to_save ['page_element_id'] = $page_element_id;
									$to_save ['page_element_content'] = CI::model ( 'template' )->parseMicrwoberTags ( $html_to_save, $options = false );
									$to_save [$field] = ($html_to_save);
									//print "<h2>For content $content_id</h2>";
									// p ( $_POST );
									// p ( $to_save );
									//p ( $html_to_save, 1 );
									$json_print [] = $to_save;
									if ($is_no_save != true) {
										//	if($to_save['content_body'])
										$saved = CI::model ( 'content' )->saveContent ( $to_save );
										//	p($to_save);
									//p($content_id);
									//p($page_id);
									//	p ( $html_to_save ,1);
									}
									//print ($html_to_save) ;
								

								//$html_to_save = CI::model ( 'template' )->parseMicrwoberTags ( $html_to_save, $options = false );
								

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
								//$html_to_save = CI::model ( 'template' )->parseMicrwoberTags ( $html_to_save, $options = false );
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
		
		//
		

		$json_print = json_encode ( $json_print );
		
	//	if ($is_no_save == true) {
			
			///	$for_history = serialize ( $json_print );
			//$for_history = base64_encode ( $for_history );
			

			$history_to_save = array ();
			$history_to_save ['table'] = 'edit';
			$history_to_save ['id'] = (parse_url ( strtolower ( $_SERVER ['HTTP_REFERER'] ), PHP_URL_PATH ));
			$history_to_save ['value'] = $json_print;
			$history_to_save ['field'] = 'html_content';
			CI::model ( 'core' )->saveHistory ( $history_to_save );
		//}
		
		print $json_print;
		
		CI::model ( 'core' )->cleanCacheGroup ( 'global/blocks' );
		exit ();
	}
	
	function html_editor_get_cache_file() {
		
		//if ((trim ( strval ( $_POST ['history_file'] ) ) != '') and strval ( $_POST ['history_file'] ) != 'false') {
		//	p ( $_POST );
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		} else {
			
			$file = ($_POST ['file']);
			if ($file) {
				if (stristr ( $file, '.php' ) == false) {
					$file = $file . '.php';
				
				}
				
				//$d = 'global';
				

				$d = CACHEDIR . 'global' . DIRECTORY_SEPARATOR . 'html_editor' . DIRECTORY_SEPARATOR;
				if (is_dir ( $d ) == false) {
					mkdir_recursive ( $d );
				}
				
				$file2 = $d . $file;
				//	p($file2);
				$content = file_get_contents ( $file2 );
				exit ( $content );
				
			// 
			}
		}
		//}
	//exit ( 1 );
	

	}
	
	function html_editor_write_cache_file() {
		
		//if ((trim ( strval ( $_POST ['history_file'] ) ) != '') and strval ( $_POST ['history_file'] ) != 'false') {
		//	p ( $_POST );
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		} else {
			
			$file = ($_POST ['file']);
			if ($file) {
				if (stristr ( $file, '.php' ) == false) {
					$file = $file . '.php';
				
				}
				
				//$d = 'global';
				

				$d = CACHEDIR . 'global' . DIRECTORY_SEPARATOR . 'html_editor' . DIRECTORY_SEPARATOR;
				if (is_dir ( $d ) == false) {
					mkdir_recursive ( $d );
				}
				
				$dir = $d;
				$dp = opendir ( $dir ) or die ( 'Could not open ' . $dir );
				while ( $filez = readdir ( $dp ) ) {
					if (($filez != '..') and ($filez != '.')) {
						if (filemtime ( $dir . $filez ) < (strtotime ( '-1 hour' ))) {
							//p ( $dir . $filez );
							unlink ( $dir . $filez );
						}
					}
				}
				closedir ( $dp );
				
				$file1 = $d . 'temp_' . $file;
				$file2 = $d . $file;
				//	require_once (LIBSPATH . "cleaner". DIRECTORY_SEPARATOR . 'class.folders.php');
				require_once (LIBSPATH . "cleaner" . DIRECTORY_SEPARATOR . 'cl.php');
				$content = ($_POST ['content']);
				$content = str_replace ( ' class="Apple-converted-space"', '', $content );
				$content = str_replace ( ' class="Apple-interchange-newline"', '', $content );
				
				$pattern = "/mw_tag_edit=\"[0-9]*\"/i";
				
				$content = preg_replace ( $pattern, "", $content );
				
				$pattern = "/mw_tag_edit=\"\"/";
				$content = preg_replace ( $pattern, "", $content );
				
				$content = clean_html_code ( $content );
				touch ( $file2 );
				//p($file2);
				file_put_contents ( $file2, $content );
				exit ( $content );
				//p ( $file );
			

			// 
			}
		}
		//}
		exit ( 1 );
	
	}
	
	function load_history_file() {
		
		if ((trim ( strval ( $_POST ['history_file'] ) ) != '') and strval ( $_POST ['history_file'] ) != 'false') {
			//	p ( $_POST );
			$id = is_admin ();
			if ($id == false) {
				exit ( 'Error: not logged in as admin.' );
			} else {
				$history_file = base64_decode ( $_POST ['history_file'] );
				//p($history_file);
				//p($history_file);
				if (strstr ( HISTORY_DIR, $history_file ) == false) {
					//exit ( 'Error: invalid history dir.' );
				}
				
				//print $history_file;
				//$history_file = $this->load->file ( $history_file, true );
				$history_file = file_get_contents ( $history_file );
				//$for_history = base64_decode ( $history_file );
				//$for_history = unserialize ( $for_history );
				

				//$history_file = CI::model ( 'template' )->parseMicrwoberTags ( $history_file );
				header ( 'Cache-Control: no-cache, must-revalidate' );
				header ( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
				header ( 'Content-type: application/json' );
				//$history_file =  ( $history_file );
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
	
	function get_url() {
		
		if ($_POST ['id']) {
			$del_id = $_POST ['id'];
		}
		
		if (url_param ( 'id' )) {
			$del_id = url_param ( 'id', true );
		}
		//p($del_id);
		if ($del_id != 0) {
			$url = (page_link ( $del_id ));
			if ($url == false) {
				
				$url = (post_link ( $del_id ));
			
			}
			
			exit ( $url );
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
			
			$j = array ();
			$j ['id'] = $save ['id'];
			$save = json_encode ( $j );
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




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
	
	function cf_save() {
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		
		if ($_POST) {
			
			$to_save = array ();
			$to_table_id = $to_table = false;
			require_once (LIBSPATH . "formbuilder/Formbuilder.php");
			//$form_data = isset ( $_POST ['ul'] ) ? $_POST ['ul'] : false;
			$form_data = isset ( $_POST ['frmb'] ) ? $_POST : false;
			$form = new Formbuilder ( $form_data );
			$for_db = $form->get_form_array ();
			
			$cf_temp = url_param ( "temp", true );
			$the_page_id = false;
			$the_post_id = false;
			$page_or_post = url_param ( "page_id", true );
			$page_or_post2 = url_param ( "post_id", true );
			
			$content_id = false;
			if (intval ( $page_or_post ) > 0) {
				$content_id = $page_or_post;
				$the_page_id = $page_or_post;
			}
			
			if (intval ( $page_or_post2 ) > 0) {
				$content_id = $page_or_post2;
				$to_table = CI::model ( 'core' )->guessDbTable ( 'content' );
				$the_post_id = $page_or_post2;
			
			}
			
			if ($cf_temp != false and $to_table_id == false and $to_table == false) {
				$to_table = $cf_temp;
				$to_table_id = $cf_temp;
			
		//exit($cf_temp);
			} else {
				
				if ($content_id != false) {
					
					$to_table_id = $content_id;
					$to_table = CI::model ( 'core' )->guessDbTable ( 'content' );
				
				}
			
			}
			
			if ($to_table_id != false and $to_table != false) {
				global $cms_db_tables;
				$custom_field_table = $cms_db_tables ['table_custom_fields'];
				$i = 1;
				$q1 = false;
				
			}
			if (! empty ( $for_db ) and $to_table_id != false and $to_table != false) {
				
				$form_data = $for_db;
				
				foreach ( $form_data as $cf_item ) {
					//p ( $form_data );
					$cf_save_data = array ();
					$cf_save_data ['custom_field_type'] = $cf_item ['cssClass'];
					$cf_save_data ['custom_field_name'] = $cf_item ['title'];
					$t = $cf_item ['title'];
					$cf_save_data ['custom_field_required'] = $cf_item ['required'];
					$cf_save_data ['custom_field_value'] = $cf_item ['values'];
					
					
					
					
					if ($the_page_id != false and $the_post_id != false) {
					$q1 = " and to_table_id !='{$the_page_id}'  ";
				}
				
				$clean = " delete from $custom_field_table where 
						to_table ='{$to_table}'
						and
						to_table_id ='{$to_table_id}'
						and	custom_field_name LIKE '{$t}'
							 
						{$q1}
						";
				
				//and	custom_field_name ='{$cf_item ['title']}'
				
 
				CI::model ( 'core' )->dbQ ( $clean );
						$clean = " delete from $custom_field_table where 
							custom_field_name LIKE ''
						";
 
				CI::model ( 'core' )->dbQ ( $clean );
				CI::model ( 'core' )->cleanCacheGroup ( 'custom_fields' );
					
					
					
					
					
					
					
					
					
					
					if (! empty ( $cf_item ['values'] )) {
						if (isset ( $cf_item ['values'] [2] ['value'] )) {
							$vvv = ($cf_item ['values'] [2] ['value']);
						} else {
							$vvv = false;
						}
						$cf_save_data ['custom_field_value'] = $vvv;
					
					}
					
					if ($cf_save_data ['custom_field_name'] == false) {
						$cf_save_data ['custom_field_name'] = $cf_item ['value'];
					}
					
					if ($cf_save_data ['custom_field_for'] == false) {
						$cf_save_data ['custom_field_for'] = $cf_item ['custom_field_for'];
					}
					
					$cf_save_data ['field_for'] = $cf_item ['custom_field_for'];
					
					if (intval ( $cf_item ['cf_id'] ) > 0) {
						//$cf_save_data ['new_id'] = $cf_item ['cf_id'];
					}
					
					if ($cf_save_data ['custom_field_name'] == false) {
						//	$cf_save_data ['custom_field_name'] = $cf_item ['values'];
					}
					
					if ($cf_save_data ['custom_field_type'] == 'input_text') {
						//$cf_save_data ['custom_field_name'] = $cf_save_data ['custom_field_value'];
					}
					
					//	$cf_save_data ['custom_field_values'] = base64_encode ( serialize ( $cf_item ['values'] ) );
					if (is_array ( $cf_item ['values'] )) {
						$cf_save_data ['custom_field_values'] = base64_encode ( json_encode ( $cf_item ['values'] ) );
					}
					$cf_save_data ['field_order'] = $i;
					
					$cf_save_data ['to_table'] = $to_table;
					
					$cf_save_data ['to_table_id'] = $to_table_id;
					//	$cf_save_data ['debug'] = $to_table_id;
					p ( $cf_save_data );
					$save = CI::model ( 'core' )->saveData ( $custom_field_table, $cf_save_data );
					
					$i ++;
				}
				
				CI::model ( 'core' )->cleanCacheGroup ( 'custom_fields' );
			}
		
		}
	
	}
	
	function cf_load() {
		
		$page_or_post = url_param ( "page_id", true );
		$page_or_post2 = url_param ( "post_id", true );
		$content_id = false;
		if (intval ( $page_or_post ) > 0) {
			$content_id = $page_or_post;
			$page_id = $page_or_post;
		}
		
		if (intval ( $page_or_post2 ) > 0) {
			$content_id = $page_or_post2;
			$to_table = CI::model ( 'core' )->guessDbTable ( 'content' );
			$post_id = $page_or_post2;
		}
		
		if ($content_id != false) {
			
			$to_table_id = $content_id;
			$to_table = CI::model ( 'core' )->guessDbTable ( 'content' );
		
		}
		
		if ($to_table_id != false and $to_table != false) {
			
			//var_dump ( $to_table, $to_table_id );
			

			$form_structure = CI::model ( 'core' )->getCustomFields ( $to_table, $to_table_id, $return_full = true );
			
			if (empty ( $form_structure ) and $page_id != false) {
				$form_structure = CI::model ( 'core' )->getCustomFields ( $to_table, $page_id, $return_full = true, "page_and_posts" );
			
			}
		}
		$fs2 = array ();
		if (! empty ( $form_structure )) {
			foreach ( $form_structure as $itm ) {
				
				if ($itm ['custom_field_type'] == "") {
					//$itm ['custom_field_type'] = $itm ['type'] = $itm ['cssClass'] = 'input_text';
				}
				
				if ($itm ['custom_field_type'] == "") {
					//$itm ['custom_field_type'] = $itm ['cssClass'] = 'input_text';
				}
				
				if ($itm ['custom_field_values'] == "") {
					//$itm ['custom_field_values'] = $itm ['values'] = array (0 => array ('value' => $itm ['custom_field_value'] ) );
				}
				
				$fs2 [] = $itm;
			}
		}
		//
		

		$fake_db_vals = ('[{"cssClass":"input_text","required":"undefined","values":"First Name"},{"cssClass":"input_text","required":"undefined","values":"Last Name"},{"cssClass":"textarea","required":"undefined","values":"Bio"},{"cssClass":"checkbox","required":"undefined","title":"What\'s on your pizza?","values":{"2":{"value":"Extra Cheese","baseline":"undefined"},"3":{"value":"Pepperoni","baseline":"undefined"},"4":{"value":"Beef","baseline":"undefined"}}}]');
		
		//p ( json_decode ( $fake_db_vals ), 1 );
		

		$form_structure_1 = json_encode ( array_to_object ( $fs2 ) );
		//p ( $form_structure );
		//	 p ( $fake_db_vals, 1 );
		//$form_structure = '[{"cssClass":"input_text","required":"undefined","values":"First Name"},{"cssClass":"input_text","required":"undefined","values":"Last Name"},{"cssClass":"textarea","required":"undefined","values":"Bio"},{"cssClass":"checkbox","required":"undefined","title":"What\'s on your pizza?","values":{"2":{"value":"Extra Cheese","baseline":"undefined"},"3":{"value":"Pepperoni","baseline":"undefined"},"4":{"value":"Beef","baseline":"undefined"}}}]';
		$form_structure = Array ();
		$form_structure ["form_id"] = 'frmb_' . rand ();
		$form_structure ["form_structure"] = $fake_db_vals;
		//	p ( $form_structure );
		$form_structure ["form_structure"] = $form_structure_1;
		//p ( $form_structure );
		//	require('Formbuilder/Formbuilder.php');
		require_once (LIBSPATH . "formbuilder/Formbuilder.php");
		// This is an arry of fake form values that should be coming from your
		// database, or data storage system. This is simply defined here for
		// example purposes only.
		$form = new Formbuilder ( $form_structure );
		$form->render_json ();
	
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
		
		if($_POST ['id'] == false){
		 if (url_param ( 'id' )) {
			$_POST ['id']  = url_param ( 'id', true );
		}
		}
		
		//p($_POST ['id']);
	//	$s = CI::model ( 'core' )->deleteDataById ( 'table_custom_fields_config', $_POST ['id'], $delete_cache_group = false );
	if($_POST ['id'] != false){
	    $s = CI::model ( 'core' )->deleteDataById ( 'table_custom_fields', $_POST ['id'], $delete_cache_group = 'global' );
	}
				
				
				
				$cf_temp = url_param ( "temp", true );
			$the_page_id = false;
			$the_post_id = false;
			$page_or_post = url_param ( "page_id", true );
			$page_or_post2 = url_param ( "post_id", true );
			
			$content_id = false;
			if (intval ( $page_or_post ) > 0) {
				$content_id = $page_or_post;
				$the_page_id = $page_or_post;
			}
			
			if (intval ( $page_or_post2 ) > 0) {
				$content_id = $page_or_post2;
				$to_table = CI::model ( 'core' )->guessDbTable ( 'content' );
				$the_post_id = $page_or_post2;
			
			}
			
		 
				
				if ($content_id != false) {
					
					$to_table_id = $content_id;
					$to_table = CI::model ( 'core' )->guessDbTable ( 'content' );
				
				}
			
		 
			
		 
				global $cms_db_tables;
				$custom_field_table = $cms_db_tables ['table_custom_fields'];
				$_POST ['name']  = url_param ( 'name', true );
			  if ($_POST ['name']) {
			
			$custom_field_to_delete = array ();
			$custom_field_to_delete ['custom_field_name'] =$_POST ['name'];
			$custom_field_to_delete ['to_table'] = $to_table;
			$custom_field_to_delete ['to_table_id'] = $to_table_id;
			p ( $custom_field_to_delete );
			$id = CI::model ( 'core' )->deleteData ( $custom_field_table, $custom_field_to_delete, 'custom_fields' );
			
			  }
		 
		 
				
				
				
				

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
			$j ['url'] = post_link ( $save ['id'] );
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
		//p($_POST); 
		if ($_POST) {
			if (intval ( $_POST ['id'] ) == 0) {
				if ($_POST ['option_key'] and $_POST ['option_group']) {
					
					CI::model ( 'core' )->optionsDeleteByKey ( $_POST ['option_key'], $_POST ['option_group'] );
				}
			}
			if (strval ( $_POST ['option_key'] ) != '') {
				//
				CI::model ( 'core' )->optionsSave ( $_POST );
			}
		
		}
	}
	
	function options_sort() {
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		$ids = $_POST ['post'];
		if (empty ( $ids )) {
			$ids = $_POST ['optitem'];
		
		}
		if (empty ( $ids )) {
			exit ();
		}
		
		$ids_implode = implode ( ',', $ids );
		global $cms_db_tables;
		$table = $cms_db_tables ['table_options'];
		
		$i = 1;
		foreach ( $ids as $id ) {
			
			$q = " UPDATE $table set position='$i' where id = '$id'    ";
			
			$q = CI::model ( 'core' )->dbQ ( $q );
			
			$i ++;
		}
		
		CI::model ( 'core' )->cacheDelete ( 'cache_group', 'options' );
		
		//var_dump($q);
		exit ();
	
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
		//$id = 1;
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
			$save_global = false;
			$save_layout = false;
			if (! empty ( $the_field_data )) {
				
				$save_global = false;
				if ($the_field_data ['attributes']) {
					//$the_field_data ['attributes'] = json_decode($the_field_data ['attributes']);
				// var_dump($the_field_data ['attributes']);
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
					$save_layout = false;
				}
				
				if (trim ( $the_field_data ['attributes'] ['rel'] ) == 'layout') {
					$save_global = false;
					$save_layout = true;
				
		//	p($the_field_data ['attributes'] ['rel']);
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
				
				$some_mods = array ();
				if (($the_field_data ['attributes'])) {
					if (($the_field_data ['html']) != '') {
						$field = trim ( $the_field_data ['attributes'] ['field'] );
						$save_global = false;
						
						if (trim ( $the_field_data ['attributes'] ['rel'] ) == 'global') {
							$save_global = true;
						
		//	 p($the_field_data ['attributes'] ['rel']);
						

						} else {
							
							$save_global = false;
						}
						
						if (trim ( $the_field_data ['attributes'] ['rel'] ) == 'layout') {
							$save_global = false;
							$save_layout = true;
						
						} else {
							$save_layout = false;
						}
						
						//	
						

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
						
						$html_to_save = str_replace ( '-handle -se', '', $html_to_save );
						$html_to_save = str_replace ( 'ui-icon ui-icon-gripsmall-diagonal-se', '', $html_to_save );
						
						$html_to_save = str_replace ( 'sizcache sizset', '', $html_to_save );
						$html_to_save = str_replace ( '<p   >', ' <p>', $html_to_save );
						$html_to_save = str_replace ( '<p >', ' <p>', $html_to_save );
						
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
						
						$content = $html->save ();
						
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
						$html_to_save = str_replace ( 'class="ui-sortable"', '', $html_to_save );
						$html_to_save = clean_word ( $html_to_save );
						
						//$a = new HtmlFixer ();
						//$a->debug = true;
						//$html_to_save =  $a->getFixedHtml ( $html_to_save );
						

						//p($save_global);
						if ($save_global == false and $save_layout == false) {
							
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
							
							if ($save_global == true and $save_layout == false) {
								$field_content = CI::model ( 'core' )->optionsGetByKey ( $the_field_data ['attributes'] ['field'], $return_full = true, $orderby = false );
								
								$html_to_save = CI::model ( 'template' )->parseToTags ( $html_to_save );
								
								//	p($html_to_save,1);
								

								$to_save = $field_content;
								$to_save ['option_key'] = $the_field_data ['attributes'] ['field'];
								$to_save ['option_value'] = $html_to_save;
								$to_save ['option_key2'] = 'editable_region';
								$to_save ['page_element_id'] = $page_element_id;
								$to_save ['page_element_content'] = CI::model ( 'template' )->parseMicrwoberTags ( $html_to_save, $options = false );
								//print "<h2>Global</h2>"; 
								

								//	p ( $to_save );
								

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
							

							}
							
							if ($save_global == false and $save_layout == true) {
								
								//	$field_content = CI::model ( 'core' )->optionsGetByKey ( $the_field_data ['attributes'] ['field'], $return_full = true, $orderby = false );
								

								$d = TEMPLATE_DIR . 'layouts' . DIRECTORY_SEPARATOR . 'editabe' . DIRECTORY_SEPARATOR;
								$f = $d . $ref_page ['id'] . '.php';
								
								if (! is_dir ( $d )) {
									mkdir_recursive ( $d );
								}
								
								//	var_dump ( $f );
								

								//
								$html_to_save = CI::model ( 'template' )->parseToTags ( $html_to_save );
								
								p ( $html_to_save );
								file_put_contents ( $f, $html_to_save );
							
		// 	p($html_to_save,1);
							

							}
						
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
	function search() {
		$res = array();
		$res[] = array('value'=>'aaa','desc'=>'aaa','accountCode'=>'aaa','accountName'=>'aaa','term'=>'aaa','countryName'=>'aaa','name'=>'aaa');
		$json_print = json_encode ( $res );
		 print $json_print;
		 exit ();
	}
	
	function save_field_old() {
		//p($_SERVER);
		$id = is_admin ();
		//$id = 1;
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
			$save_global = false;
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
								

								p ( $to_save );
								
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
				header ( 'Content-type: application/json; charset=utf-8' );
				//header ( "Content-type: text/html");
				//$history_file = preg_replace('/[^(\x20-\x7F)]*/','', $history_file);
				$history_file = preg_replace ( "/[�]/", "", $history_file );
				//$history_file =  ( $history_file );
				print $history_file;
				exit ();
			
		// 
			}
		}
	
	}
	
	function load_layout_element() {
		if (! defined ( 'PAGE_ID' )) {
			
			if (intval ( PAGE_ID ) == 0) {
				
				$p = url ( $skip_ajax = false );
				$page = CI::model ( 'content' )->getPageByURLAndCache ( $p );
				
				define ( "PAGE_ID", $page ['id'] );
			
			}
		
		}
		if ($_POST ['element']) {
			
			$e = $_POST ['element'];
			$dir_name = normalize_path ( ELEMENTS_DIR . DIRECTORY_SEPARATOR . $e, false );
			$f = $dir_name . '.php';
			$f = normalize_path ( $f, false );
			//p($f);
			if (is_file ( $f )) {
				$file = $this->load->file ( $f, true );
				$file = CI::model ( 'template' )->parseMicrwoberTags ( $file );
				exit ( $file );
			}
		
		//	p ( $dir_name );
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
	
	function option_delete() {
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		
		$post_id = $_POST ['id'];
		if ($post_id) {
			CI::model ( 'core' )->optionsDeleteById ( $post_id );
			exit ( 'yes' );
		} else {
			exit ( 'Error: invalid id' );
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
	function temp2() {
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		
		global $cms_db_tables;
		$table_content = $cms_db_tables ['table_content'];
		
		$q = " SELECT *  from $table_content where  content_type='post'";
		
		$q = CI::model ( 'core' )->dbQuery ( $q, md5 ( $q ), 'content/global' );
		
		foreach ( $q as $post ) {
			$d1 = cf_val ( $post ['id'], 'dimensions' );
			// p($d1);
			

			if (stristr ( $d1, 'case' )) {
				$hs = 'Case';
			} else {
				$hs = 'Each';
			}
			
			$d2 = get_string_between ( $d1, $hs, 'lbs.' );
			$d2 = str_replace ( '&lt;/strong&gt;&lt;br/&gt;', 'aaa ', $d2 );
			$d2 = $d2 . ' --end ';
			
			$wide = get_string_between ( $d2, 'aaa', 'in. wide' );
			$high = get_string_between ( $d2, 'in. wide x', 'in. high x' );
			$deep = get_string_between ( $d2, 'in. high x', 'in. deep' );
			$weight = get_string_between ( $d2, 'Weight:', '--end' );
			
			$wide = floatval ( $wide );
			$high = floatval ( $high );
			$deep = $length = floatval ( $deep );
			$weight = floatval ( $weight );
			
			
			$to_save = array();
			
			
			$to_save ['id'] = $post ['id'];
			$to_save ['custom_field_length'] = $deep;
			$to_save ['custom_field_width'] = $wide;
			$to_save ['custom_field_height'] = $high;
			$to_save ['custom_field_weight'] = $weight;
			
			p($to_save);
			
			$save = CI::model ( 'core' )->saveData ( $table_content, $to_save );
			//$to_save = post_save($to_save);
			
			/*p ( $d2 );
			p ( $wide );
			p ( $high );
			p ( $deep );
			p ( $weight );*/
		
		}
	
	}
	
	function temp() {
		$id = is_admin ();
		if ($id == false) {
			exit ( 'Error: not logged in as admin.' );
		}
		
		$dir = ACTIVE_TEMPLATE_DIR . 'temp' . DS . 'perfect10' . DS;
		
		$dir2 = ACTIVE_TEMPLATE_DIR . 'temp' . DS . 'perfect10_done' . DS;
		
		@mkdir ( $dir2 );
		
		require_once (LIBSPATH . "simplehtmldom/simple_html_dom.php");
		
		foreach ( glob ( "{$dir}*.*" ) as $filename ) {
			
			$filename_done = basename ( $filename );
			$filename_done1 = $dir2 . ($filename_done);
			
			//	p($filename_done,1);
			

			$content = file_get_contents ( $filename );
			$html = str_get_html ( $content );
			
			// Find all article blocks
			foreach ( $html->find ( 'div.detail-wrap' ) as $article ) {
				$item = array ();
				$item ['content_title'] = $article->find ( 'span.productName', 0 )->plaintext;
				$item ['custom_field_price'] = $article->find ( 'span.pricingPrice', 0 )->plaintext;
				$item ['custom_field_price'] = str_ireplace ( '$', '', $item ['custom_field_price'] );
				$item ['custom_field_original_price'] = $item ['custom_field_price'];
				
				p ( $item ['custom_field_price'] );
				$percent = '20'; // without %
				$total = $item ['custom_field_price']; // initial value
				

				/* Calculate $percent% from $total */
				$discount_value = ($total / 100) * $percent;
				
				$final_price = $total + $discount_value;
				
				// Format numbers with number_format()
				

				$final_price = number_format ( $final_price );
				$item ['custom_field_price'] = $final_price;
				p ( $item ['custom_field_price'] );
				
				$item ['content_title'] = str_ireplace ( '&', 'and', $item ['content_title'] );
				$item ['content_title'] = str_ireplace ( '/', '-', $item ['content_title'] );
				$item ['content_title'] = str_ireplace ( '"', 'inch', $item ['content_title'] );
				
				$item ['custom_field_model'] = $article->find ( 'span.productItemID', 0 )->plaintext;
				$item ['content_parent'] = 3484;
				
				$item ['custom_field_dimensions'] = $article->find ( 'span[id=ctl00_ctl00_MasterPlaceHolder_MainContent_lblDimensions]', 0 )->innertext;
				
				$item ['custom_field_cats'] = $article->find ( 'span[id=ctl00_ctl00_MasterPlaceHolder_MainContent_lblProductBreadCrumbs]', 0 )->plaintext;
				$item ['custom_field_cats'] = str_ireplace ( '&nbsp;', ' ', $item ['custom_field_cats'] );
				
				//$item['taxonomy_categories_str'] = explode('>',  $item['custom_field_cats']);
				

				$c = explode ( '>', $item ['custom_field_cats'] );
				;
				$tax_cats = array ();
				$ci1 = 0;
				foreach ( $c as $c1 ) {
					$c1 = trim ( $c1 );
					
					$c1 = str_ireplace ( '&', 'and', $c1 );
					$c1 = str_ireplace ( '/', '-', $c1 );
					
					$c123 = get_category ( $c1 );
					
					$c123_par = get_category ( $c [$ci1 - 1] );
					
					$c_data = array ();
					
					$c_data ['parent_id'] = 0;
					if ($c123 != false) {
						$c_data ['id'] = $c123 ['id'];
					
					}
					$c_data ['parent_id'] = intval ( $c123_par ['id'] );
					if ($c_data ['parent_id'] == 0) {
						$c_data ['parent_id'] = 100002115;
					}
					
					$c_data ['to_table'] = 'table_content';
					$c_data ['content_type'] = 'post';
					$c_data ['taxonomy_value'] = $c1;
					$c_data ['taxonomy_type'] = 'category';
					
					//p($c_data);
					if ($c_data ['taxonomy_value'] != '') {
						$add_new = CI::model ( 'taxonomy' )->taxonomySave ( $c_data, $preserve_cache = true );
						$tax_cats [] = $add_new;
					}
					
					p ( $add_new );
					$ci1 ++;
				
				}
				
				$item ['custom_field_specs'] = $article->find ( 'span[id=ctl00_ctl00_MasterPlaceHolder_MainContent_rptProductText_ctl01_lblProductText]', 0 )->innertext;
				$the_pdf = false;
				if (stristr ( $item ['custom_field_specs'], 'href="/webstore/' )) {
					
					$item ['custom_field_specs'] = str_ireplace ( 'href="/webstore/', 'href="https://www.perfect-10.tv/webstore/', $item ['custom_field_specs'] );
					$item ['custom_field_specs'] = str_ireplace ( 'src="/webstore/', 'src="https://www.perfect-10.tv/webstore/', $item ['custom_field_specs'] );
					
					$html2 = str_get_html ( $item ['custom_field_specs'] );
					$pdf_in_speck = $html2->find ( 'a', 0 )->href;
					
					if (stristr ( $pdf_in_speck, '.pdf' )) {
						$the_pdf = $pdf_in_speck;
						
						$html2->find ( 'a', 0 )->outertext = '';
						$item ['custom_field_specs'] = $html2->save ();
					
					}
				}
				
				if ($the_pdf == false) {
					$pdf1 = $article->find ( 'span[id=ctl00_ctl00_MasterPlaceHolder_MainContent_rptProductText_ctl02_lblProductText]', 0 )->innertext;
					$html2 = str_get_html ( $pdf1 );
					$item ['custom_field_pdf'] = $html2->find ( 'a', 0 )->href;
					$item ['custom_field_pdf'] = 'https://www.perfect-10.tv' . $item ['custom_field_pdf'];
				} else {
					$item ['custom_field_pdf'] = $the_pdf;
				}
				
				if (stristr ( $item ['custom_field_pdf'], '.pdf' )) {
					//p($item ['custom_field_pdf']);
					//	url_getPageToFile($url, $newfilename) 
					$loacl_pdf = basename ( $item ['custom_field_pdf'] );
					
					$f1234z = USERFILES_DIR . 'pdf/' . $loacl_pdf;
					$f1234z = USERFILES_DIR . 'media/pdf/';
					$f1234z = normalize_path ( $f1234z, true );
					@mkdir ( $f1234z );
					$f1234zf = $f1234z . $loacl_pdf;
					
					CI::model ( 'core' )->url_getPageToFile ( $item ['custom_field_pdf'], $f1234zf );
					$f1234zf = dir2url ( $f1234zf );
					$item ['custom_field_pdf'] = $f1234zf;
				
		//p ( $f1234zf ,1);
				

				//	exit ( $item ['custom_field_pdf'] );
				

				}
				
				$item ['content_body'] = $article->find ( 'span[id=ctl00_ctl00_MasterPlaceHolder_MainContent_rptProductText_ctl00_lblProductText]', 0 )->innertext;
				
				$item ['image'] = $article->find ( 'img[id=ctl00_ctl00_MasterPlaceHolder_MainContent_rptImages_ctl00_Img1]', 0 )->src;
				
				$item ['image'] = 'https://www.perfect-10.tv/WebStore/' . $item ['image'];
				$item ['image'] = str_ireplace ( 'Thumbnails/', '', $item ['image'] );
				//   $item['image']  = str_ireplace('https://', 'http://',  $item['image']);
				

				//   $item['image'] = reduce_double_slashes( $item['image']);
				$item ['taxonomy_categories'] = $tax_cats;
				$item ['screenshot_url'] = $item ['image'];
				$item ['content_type'] = 'post';
				
				/// p($item['content_title']);
				

				$is_saved_already = array ();
				$is_saved_already ['content_title'] = $item ['content_title'];
				$is_saved_already ['content_type'] = 'post';
				//  $is_post = get_posts( $is_saved_already);
				

				$itit = url_title ( $is_saved_already ['content_title'] );
				$is_p = CI::model ( 'content' )->getContentByURLAndCache ( $itit );
				//. p($is_p);
				//  $is_post = CI::model ( 'content' )->getContentAndCache ( $is_post, $orderby1, array (0, 10 ), $short_data = false, $only_fields = array ('id', 'content_title', 'content_body', 'content_url', 'content_filename', 'content_parent', 'content_filename_sync_with_editor', 'content_body_filename' ) );
				

				//  p( $is_post,1);
				$item ['id'] = false;
				if (! empty ( $is_p )) {
					$id_id = $is_p ['id'];
					
					//p($id_id);
					$item ['id'] = $id_id;
				}
				// p($item );
				p ( $item );
				
				$to_save = CI::model ( 'content' )->saveContent ( $item, $delete_the_cache = false );
			
		// /
			

			}
			
			/*
			
					$html = str_get_html ( $content );
					foreach ( $html->find ( 'div[field]' ) as $checkbox ) {
						//var_Dump($checkbox);
						$re1 = $checkbox->rel;
						$style = $checkbox->field;
						
						if ($checkbox->page) {
							$checkbox->page = "{PAGE_ID}";
						}
						
						if ($checkbox->post) {
							$checkbox->post = "{POST_ID}";
						}
						
						$re2 = $checkbox->mw_params_module;
						
						$inner = $checkbox->innertext;
						
						$tag1 = "<editable ";
						$tag1 = $tag1 . "rel=\"{$re1}\" ";
						
						$tag1 = $tag1 . "field=\"{$style}\" ";
						$tag1 .= ">" . $inner . "</editable>";
						//p($tag1);
						$checkbox->outertext = $tag1;
						$html->save ();
					
					}
					
					$content = $html->save ();
				*/
			
			var_dump ( $filename, $filename_done1 );
			rename ( $filename, $filename_done1 );
		
		}
		
		exit ( 123 );
	
	}

}




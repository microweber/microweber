<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
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

 * Comments class

 *

 * @desc Functions for manipulation comments

 * @access      public
 * @category   Taxonomy API
 * @subpackage      Core
 * @author      Peter Ivanov
 * @link        http://ooyes.net

 */

class Comments_model extends Model {
	function __construct() {
		parent::Model ();
	
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
			
		//	$orderby = $orderby;
		

		}
		
		$cache_group = "comments/global";
		if (intval ( $data ['id'] ) != 0) {
			$cache_group = "comments/{$data['id']}";
		}
		
		if ((trim ( $data ['to_table'] ) != '') and (trim ( $data ['to_table_id'] ) != '')) {
			$cache_group = "comments/{$data ['to_table']}/{$data ['to_table_id']}";
		}
		
		$get = CI::model('core')->getDbData ( $table, $data, $limit, $offset = false, $orderby, $cache_group, false, $ids = false, $count_only );
		
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
						$id = $comment ['to_table_id'];
						$real_comments [] = $comment;
					
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
		//p($q);
	 
		$q = CI::model('core')->dbQuery ( $q , __FUNCTION__.md5($q), 'comments/global');
		
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
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_comments'];
		
		$master_table = CI::model('core')->dbGetRealDbTableNameByAssocName ( $to_table );
		
		$where = "WHERE comments.is_moderated = 'n'";
		
		if ($only_ids !== null && is_array ( $only_ids )) {
			
			$where = "\n AND comments.to_table_id IN (" . implode ( ',', $only_ids ) . ")";
		
		}
		
		$query = "

            select

                COUNT(comments.id) AS comments_total,

                master_table.id AS item_id

            FROM

                {$table} AS comments

            INNER JOIN

                {$master_table} AS master_table

            ON

                (comments.to_table = '{$to_table}' AND comments.to_table_id = master_table.id)

            {$where}

            GROUP BY

                comments.to_table, comments.to_table_id

        ";
		
		//      CI::model('core')->cleanCacheGroup('comments');
		

		$qty = CI::model('core')->dbQuery ( $query, $function_cache_id . md5 ( $query ), 'comments/global' );
		
		return $qty;
	
	}
	
	function commentsGetNewCommentsCount() {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_comments'];
		
		$data ['is_moderated'] = 'n';
		
		$orderby [0] = 'updated_on';
		
		$orderby [1] = 'DESC';
		
		$get = CI::model('core')->getDbData ( $table, $criteria = $data, $limit = false, $offset = false, $orderby = false, $cache_group = 'comments/global', $debug = false, $ids = false, $count_only = true );
		
		return $get;
	
	}
	
	function commentsSave($data) {
		
		global $cms_db_tables;
		
		$data = stripFromArray ( $data );
		
		$data = htmlspecialchars_deep ( $data );
		
		$table = $cms_db_tables ['table_comments'];
		
		//$data_to_save_options ['delete_cache_groups'] = array ('comments' );
		

		$id = CI::model('core')->saveData ( $table, $data );
		
		if (intval ( $id ) != 0) {
			CI::model('core')->cleanCacheGroup ( 'comments/' . $id );
		}
		CI::model('core')->cleanCacheGroup ( 'comments/global' );
		
		if ((trim ( $data ['to_table'] ) != '') and (trim ( $data ['to_table_id'] ) != '')) {
			$cache_group = "comments/{$data['to_table']}/{$data['to_table_id']}";
			//var_dump($cache_group);
			CI::model('core')->cleanCacheGroup ( $cache_group );
		}
		
		return $id;
	
	}
	
	function commentsDeleteById($id) {
		if (intval ( $id ) != 0) {
			global $cms_db_tables;
			
			$data = $this->commentGetById ( $id );
			
			$table = $cms_db_tables ['table_comments'];
			
			CI::model('core')->deleteDataById ( $table, $id );
			
			CI::model('core')->cleanCacheGroup ( 'comments/' . $id );
			CI::model('core')->cleanCacheGroup ( 'comments/global' );
			
			if ((trim ( $data ['to_table'] ) != '') and (trim ( $data ['to_table_id'] ) != '')) {
				$cache_group = "comments/{$data ['to_table']}/{$data ['to_table_id']}";
				CI::model('core')->cleanCacheGroup ( $cache_group );
			}
			
			return true;
		}
	
	}

}


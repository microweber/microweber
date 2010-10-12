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

 * Votes class

 *

 * @desc Functions for manipulation votes

 * @access      public
 * @category   Taxonomy API
 * @subpackage      Core
 * @author      Peter Ivanov
 * @link        http://ooyes.net

 */

class votes_model extends Model {
	function __construct() {
		parent::Model ();
	
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
			
			$created_by = $this->core_model->userId ();
			
			//p($created_by, 1);
			

			if ($created_by > 0) {
				
				$check_if_user_voted_for_today = " SELECT count(*) as qty

            from $table

            where to_table='$to_table' and  to_table_id='$to_table_id'

            and created_by=$created_by





            ";
			
			} else {
				
				$ip = visitorIP ();
				
				$yesterday = date ( 'Y-m-d H:i:s', mktime ( 0, 0, 0, date ( "m" ), date ( "d" ) - 1, date ( "Y" ) ) );
				
				$check_if_user_voted_for_today = " SELECT count(*) as qty

            from $table

            where to_table='$to_table' and  to_table_id='$to_table_id'

            and created_on > '$yesterday'

            and user_ip = '$ip'



            ";
			
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
			
			$qty = " SELECT count(*) as qty

            from $table

            where to_table='$to_table' and  to_table_id='$to_table_id'

            and created_on > '$yesterday'

            ";
			
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
		
		$query = "

            select

                COUNT(votes.id) AS votes_total,

                master_table.id AS item_id

            FROM

                {$table} AS votes

            INNER JOIN

                {$master_table} AS master_table

            ON

                (votes.to_table = '{$to_table}' AND votes.to_table_id = master_table.id)

            {$where}

            GROUP BY

                votes.to_table, votes.to_table_id

        ";
		
		//      $this->core_model->cleanCacheGroup ( 'votes');
		

		return $this->core_model->dbQuery ( $query, md5 ( $query ), 'votes' );
	
	}

}


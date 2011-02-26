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

 * Content class

 *

 * @desc Functions for manipulation reports

 * @access      public
 * @category   Taxonomy API
 * @subpackage      Core
 * @author      Peter Ivanov
 * @link        http://ooyes.net

 */

class reports_model extends Model {
	function __construct() {
		parent::Model ();
	
	}
	
	function report($to_table, $to_table_id, $user_id = false) {
		
		if ((trim ( $to_table ) != '') and (trim ( $to_table_id ) != '')) {
			$cache_group = "reports/{$to_table}/{$to_table_id}";
		} else {
			$cache_group = 'reports/global';
		
		}
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_reports'];
		
		$the_table = CI::model('core')->dbGetRealDbTableNameByAssocName ( $to_table );
		
		if ($user_id == false) {
			$created_by = CI::model('core')->userId ();
		} else {
			$created_by = $user_id;
		}
		
		if ($created_by > 0) {
			
			$check_if_user_reported_for_today = " SELECT count(*) as qty

            from $table
            where to_table='$to_table' and  to_table_id='$to_table_id'
            and created_by=$created_by
            ";
		
		} else {
			
			$ip = visitorIP ();
			
			$yesterday = date ( 'Y-m-d H:i:s', mktime ( 0, 0, 0, date ( "m" ), date ( "d" ) - 1, date ( "Y" ) ) );
			
			$check_if_user_reported_for_today = " SELECT count(*) as qty

            from $table

            where to_table='$to_table' and  to_table_id='$to_table_id'

            and created_on > '$yesterday'

            and user_ip = '$ip'



            ";
		
		}
		
		//var_dump ( $check_if_user_reported_for_today );
		

		$check_if_user_reported_for_today = CI::model('core')->dbQuery ( $check_if_user_reported_for_today, __FUNCTION__ . md5 ( $check_if_user_reported_for_today ), $cache_group );
		
		$check_if_user_reported_for_today = intval ( $check_if_user_reported_for_today [0] ['qty'] );
		
		if ($check_if_user_reported_for_today == 0) {
			
			$cast_vote = array ();
			
			$cast_vote ['to_table'] = $to_table;
			
			$cast_vote ['to_table_id'] = $to_table_id;
			
			$cast_vote ['user_ip'] = $ip;
			
			CI::model('core')->saveData ( $table, $cast_vote );
			
			CI::model('core')->cleanCacheGroup ( $cache_group );
			CI::model('core')->cleanCacheGroup ( 'reports/global' );
			
			return true;
		
		} else {
			
			return false;
		
		}
	
	}
	
	function reportsGet($params, $db_options = false) {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_reports'];
		
		if (empty ( $options ['order'] )) {
			
			$orderby [0] = 'created_on';
			
			$orderby [1] = 'DESC';
			
			$options ['order'] = $orderby;
		
		} else {
			
			$options ['order'] = $options ['order'];
		
		}
		
		if ((trim ( $params ['to_table'] ) != '') and (trim ( $params ['to_table_id'] ) != '')) {
			$cache_group = "reports/{$params['to_table']}/{$params['to_table_id']}";
		} else {
			$cache_group = 'reports/global';
		
		}
		
		$options = array ();
		
		$options ['get_params_from_url'] = true;
		
		$options ['debug'] = false;
		
		$options ['items_per_page'] = 100;
		$options ['group_by'] = 'to_table, to_table_id';
		$options ['cache'] = true;
		$options ['cache_group'] = $cache_group;
		
		if (! empty ( $db_options )) {
			
			foreach ( $db_options as $k => $v ) {
				
				$options ["{$k}"] = $v;
			
			}
		
		}
		
		$data = CI::model('core')->fetchDbData ( $table, $params, $options );
		
		return $data;
	
	}
	
	function reportsGetCount($to_table, $to_table_id, $since_time = false) {
		
		if ($since_time == false) {
			
			$since_time = ' 1 year ';
		
		}
		
		if (($timestamp = strtotime ( $since_time )) === false) {
			
			return FALSE;
		
		}
		
		if ((trim ( $to_table ) != '') and (trim ( $to_table_id ) != '')) {
			$cache_group = "reports/{$to_table}/{$to_table_id}";
		} else {
			$cache_group = 'reports/global';
		
		}
		
		$args = func_get_args ();
		
		foreach ( $args as $k => $v ) {
			
			$function_cache_id = $function_cache_id . serialize ( $k ) . serialize ( $v );
		
		}
		
		$function_cache_id = __FUNCTION__ . md5 ( $function_cache_id );
		
		$cache_content = CI::model('core')->cacheGetContentAndDecode ( $function_cache_id, $cache_group );
		
		if (($cache_content) != false) {
			
			if ($cache_content == 'false') {
				
				return 0;
			
			} else {
				
				return $cache_content;
			
			}
		
		}
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_reports'];
		
		$the_table = CI::model('core')->dbGetRealDbTableNameByAssocName ( $to_table );
		
		$check = CI::model('core')->dbCheckIfIdExistsInTable ( $the_table, $to_table_id );
		
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
			

			$qty = CI::model('core')->dbQuery ( $qty, $cache_id = __FUNCTION__ . md5 ( $qty ), $cache_group );
			
			$qty = $qty [0] ['qty'];
			
			$qty = intval ( $qty );
			
			if ($qty == 0) {
				
				CI::model('core')->cacheWriteAndEncode ( 'false', $function_cache_id, $cache_group );
			
			} else {
				
				CI::model('core')->cacheWriteAndEncode ( $qty, $function_cache_id, $cache_group );
			
			}
			
			return $qty;
		
		}
	
	}

}


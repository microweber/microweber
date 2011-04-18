<?php

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

 * @filesource

 */

// ------------------------------------------------------------------------


/**

 * Cart Class

 *

 * @desc Stats class

 * @access      public

 * @category    Stats API

 * @subpackage      Stats

 * @author      Peter Ivanov

 * @link        http://ooyes.net

 */

class Stats_model extends Model {
	
	function __construct() {
		
		parent::Model ();
	
	}
	
	function site_id() {
		
		global $cms_db_tables;
		
		$table = $cms_db_tables ['table_stats_site'];
		$table_access = $cms_db_tables ['table_stats_access'];
		$url = site_url ();
		$cache_group = 'stats';
		
		$q = "SELECT * FROM $table WHERE main_url = '{$url}' ";
		$q = CI::model ( 'core' )->dbQuery ( $q, __FUNCTION__ . md5 ( $q ), $cache_group );
		if (empty ( $q )) {
			$now = date ( 'Y-m-d H:i:s' );
			$q1 = "insert into $table set main_url = '{$url}', name= '{$url}', timezone='America/New_York', ts_created='{$now}' ";
			$q1 = CI::model ( 'core' )->dbQ ( $q1 );
			CI::model ( 'core' )->cleanCacheGroup ( 'stats' );
		} else {
			//p ( $q );
			$id = $q [0] ['idsite'];
			$id = intval ( $id );
			
			$q = "SELECT * FROM $table_access WHERE login = 'anonymous' and idsite=$id";
			$q = CI::model ( 'core' )->dbQuery ( $q, __FUNCTION__ . md5 ( $q ), $cache_group );
			if (empty ( $q )) {
				$q1 = "insert into $table_access set login = 'anonymous', idsite=$id, access='view' ";
				$q1 = CI::model ( 'core' )->dbQ ( $q1 );
				CI::model ( 'core' )->cleanCacheGroup ( 'stats' );
			}
			
			return $id;
		}
	
	}
	
	function get_js_code() {
		$id = $this->site_id ();
		if ($id == 0) {
			return false;
		}
		$piwik_url = site_url ( 'system/application/stats/' ) . '/';
		$js = "
	
	
	<!-- Piwik --> 
<script type=\"text/javascript\">
var pkBaseURL = ((\"https:\" == document.location.protocol) ? \"{$piwik_url}\" : \"{$piwik_url}\");
document.write(unescape(\"%3Cscript src=\'\" + pkBaseURL + \"piwik.js\' type=\'text/javascript\'%3E%3C/script%3E\"));
</script><script type=\"text/javascript\">
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + \"piwik.php\", {$id});
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
</script><noscript><p><img src=\"http://{$piwik_url}piwik.php?idsite={$id}\" style=\"border:0\" alt=\"\" /></p></noscript>
<!-- End Piwik Tracking Code -->
	
	
	
	";
		return $js;
	
	}

}

?>
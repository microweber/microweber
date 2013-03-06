<?

if (!defined('APC_CACHE')) {

	$apc_exists = function_exists('apc_fetch');

	if (isset($_SESSION)) {
		$is_editmode = session_get('editmode');
		if ($is_editmode and intval($is_editmode) == 1) {
		//	$apc_exists = false;
		}
	}
	if (isset($_POST) and isarr($_POST)) {
	///	$apc_exists = false;
	}
	//$apc_exists = false;
	//    if (isset($_COOKIE['editmode'])) {
	//
	//    }
	//$apc_exists = isset($_GET['test_cookie']);
	//d($apc_exists);
	define("APC_CACHE", $apc_exists);
}
  $_mw_cache_obj = false;
$enable_server_cache_storage = static_option_get('enable_server_cache_storage', 'server');
if ($enable_server_cache_storage != false and $enable_server_cache_storage != 'default') {
	if ($enable_server_cache_storage != 'default') {
		$cache_storage_type = "mw\cache\\" . trim($enable_server_cache_storage);

		try {
			$_mw_cache_obj = new $cache_storage_type;
		} catch (Exception $e) {
			echo 'Caught exception: ', $e -> getMessage(), "\n";
		}

	}
} else {

	if ($_mw_cache_obj == false) {
		/*
		if ($apc_exists == true) {
					$_mw_cache_obj = new mw\cache\apc();
				} else {*/
		
			$_mw_cache_obj = new mw\cache\files();

	//	}
	}
}
/*
 // if(isset($_GET['debug'])){
 // d($enable_server_cache_storage);
 // }*/

if (!defined('APC_EXPIRES')) {

	define("APC_EXPIRES", 3000);
}

/**
 *
 *
 * Deletes cache directory for given $cache_group recursively.
 *
 * @param string $cache_group
 *        	(default is 'global') - this is the subfolder in the cache dir.
 * @return boolean
 * @author Peter Ivanov
 * @since Version 1.0
 */
mw_var('is_cleaning_now', false);
function cache_clean_group($cache_group = 'global', $cache_storage_type = false) {
	if ($cache_storage_type == false) {
		global $_mw_cache_obj;
		$local_obj = $_mw_cache_obj;
	} else {
		$cache_storage_type = "\mw\cache\\" . $cache_storage_type;
		$local_obj = new $cache_storage_type;

	}
	//d($local_obj);
	  $local_obj -> delete($cache_group);
}

/**
 *
 *
 *
 * Gets the data from the cache.
 *
 *
 * Unserilaizer for the saved data from the cache_get_content_encoded() function
 *
 * @param string $cache_id
 *        	of the cache
 * @param string $cache_group
 *        	(default is 'global') - this is the subfolder in the cache dir.
 *
 * @return mixed
 * @author Peter Ivanov
 * @since Version 1.0
 * @uses cache_get_content_encoded
 */

function cache_get_content($cache_id, $cache_group = 'global', $cache_storage_type = false) {

	if ($cache_storage_type == false) {
		global $_mw_cache_obj;
		$local_obj = $_mw_cache_obj;
	} else {
		$cache_storage_type = "\mw\cache\\" . $cache_storage_type;
		$local_obj = new $cache_storage_type;

	}

	return $local_obj -> get($cache_id, $cache_group);
}

/**
 *
 *
 * Stores your data in the cache.
 * It can store any value, such as strings, array, objects, etc.
 *
 * @param mixed $data_to_cache
 *        	your data
 * @param string $cache_id
 *        	of the cache, you must define it because you will use it later to
 *        	load the file.
 * @param string $cache_group
 *        	(default is 'global') - this is the subfolder in the cache dir.
 *
 * @return boolean
 * @author Peter Ivanov
 * @since Version 1.0
 * @uses cache_write_to_file
 */

function cache_save($data_to_cache, $cache_id, $cache_group = 'global', $cache_storage_type = false) {

	if ($cache_storage_type == false) {
		global $_mw_cache_obj;
		$local_obj = $_mw_cache_obj;
	} else {
		$cache_storage_type = "\mw\cache\\" . $cache_storage_type;
		$local_obj = new $cache_storage_type;

	}
	return $local_obj -> save($data_to_cache, $cache_id, $cache_group);

}

api_expose('clearcache');

function clearcache($cache_storage_type = false) {
	if ($cache_storage_type == false) {
		global $_mw_cache_obj;
		$local_obj = $_mw_cache_obj;
	} else {
		$cache_storage_type = "\mw\cache\\" . $cache_storage_type;
		$local_obj = new $cache_storage_type;

	}

	return $local_obj -> purge();
}

function cache_debug($cache_storage_type = false) {
	global $_mw_cache_obj;
	return $_mw_cache_obj -> debug();
}

<?php
/**
 * Here you will find how to work with the cache
 *
 * These functions will allow you to save and get data from the MW cache system
 *
 * @package cache
 */


/*if (!defined('APC_CACHE')) {

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
}*/

//$_mw_cache_obj = false;
/*$enable_server_cache_storage = static_option_get('enable_server_cache_storage', 'server');
if ($enable_server_cache_storage != false and $enable_server_cache_storage != 'default') {
	if ($enable_server_cache_storage != 'default') {
		$cache_storage_type = "\mw\cache\\" . trim($enable_server_cache_storage);

		try {
			$_mw_cache_obj = new $cache_storage_type;
		} catch (Exception $e) {
			echo 'Caught exception: ', $e -> getMessage(), "\n";
		}

	}
} else {


}*/

if (!isset($_mw_cache_obj) or $_mw_cache_obj == false) {
    $_mw_cache_obj = new \mw\cache\Files();
}


mw_var('is_cleaning_now', false);
/**
 * Deletes cache directory for given $cache_group recursively.
 *
 * @param string $cache_group
 *            (default is 'global') - this is the subfolder in the cache dir.
 * @param bool $cache_storage_type
 * @return boolean
 *
 * @package cache
 * @category  cache
 *
 */
function cache_clean_group($cache_group = 'global', $cache_storage_type = false)
{
    if ($cache_storage_type == false) {
        global $_mw_cache_obj;
        $local_obj = $_mw_cache_obj;
    } else {
        $cache_storage_type = "\mw\cache\\" . $cache_storage_type;
        $local_obj = new $cache_storage_type;

    }
    //d($cache_group);
    $local_obj->delete($cache_group);
}

/**
 * Gets the data from the cache.
 *
 *
 *  return array of cached data
 *
 * @param string $cache_id
 *            of the cache
 * @param string $cache_group
 *            (default is 'global') - this is the subfolder in the cache dir.
 *
 * @param bool $cache_storage_type
 * @return mixed
 * @package cache
 * @category  cache
 *
 */
function cache_get_content($cache_id, $cache_group = 'global', $cache_storage_type = false)
{

    if ($cache_storage_type == false) {
        global $_mw_cache_obj;
        $local_obj = $_mw_cache_obj;
    } else {
        $cache_storage_type = "\mw\cache\\" . $cache_storage_type;
        $local_obj = new $cache_storage_type;

    }

    return $local_obj->get($cache_id, $cache_group);
}

/**
 * Stores your data in the cache.
 * It can store any value, such as strings, array, objects, etc.
 *
 * @param mixed $data_to_cache
 *            your data, anything that can be serialized
 * @param string $cache_id
 *            id of the cache, you must define it because you will use it later to
 *            retrieve the cached content.
 * @param string $cache_group
 *            (default is 'global') - this is the subfolder in the cache dir.
 *
 * @param bool $cache_storage_type
 * @return boolean
 * @package cache
 * @category  cache
 */
function cache_save($data_to_cache, $cache_id, $cache_group = 'global', $cache_storage_type = false)
{

    if ($cache_storage_type == false) {
        global $_mw_cache_obj;
        $local_obj = $_mw_cache_obj;
    } else {
        $cache_storage_type = "\mw\cache\\" . $cache_storage_type;
        $local_obj = new $cache_storage_type;

    }
    // d($data_to_cache);
    return $local_obj->save($data_to_cache, $cache_id, $cache_group);

}


api_expose('clearcache');
/**
 * Clears all cache
 *
 * @param bool $cache_storage_type
 * @return boolean
 * @package cache
 * @category  cache
 */
function clearcache($cache_storage_type = false)
{
    if ($cache_storage_type == false or trim($cache_storage_type) == '') {
        global $_mw_cache_obj;
        $local_obj = $_mw_cache_obj;
    } else {
        $cache_storage_type = "\mw\cache\\" . $cache_storage_type;
        $local_obj = new $cache_storage_type;

    }

    return $local_obj->purge();
}

/**
 * Prints cache debug information
 *
 * @return array
 * @package cache
 * @category  cache
 */
function cache_debug()
{
    global $_mw_cache_obj;
    return $_mw_cache_obj->debug();
}

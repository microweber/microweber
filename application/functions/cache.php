<?php
/**
 * Here you will find how to work with the cache
 * These functions will allow you to save and get data from the MW cache system
 *
 * @package Cache
 * @category Cache
 * @desc  These functions will allow you to save and get data from the MW cache system
 */

if (!isset($_mw_cache_obj) or is_object($_mw_cache_obj) == false) {
    $_mw_cache_obj = new \mw\cache\Files();
}


mw_var('is_cleaning_now', false);
/**
 * Deletes cache for given $cache_group recursively.
 *
 * @param string $cache_group
 *            (default is 'global') - this is the subfolder in the cache dir.
 * @param bool $cache_storage_type
 * @return boolean
 *
 * @package Cache
 * @example 
 * <code>
 * //delete the cache for the content
 *  cache_clean_group("content");
 *
 * //delete the cache for the content with id 1
 *  cache_clean_group("content/1");
 *
 * //delete the cache for users
 *  cache_clean_group("users");
 *
 * //delete the cache for your custom table eg. my_table
 * cache_clean_group("my_table");
 * </code>
 *
 */
function cache_clean_group($cache_group = 'global', $cache_storage_type = false)
{
    if ($cache_storage_type == false  or $cache_storage_type == 'files') {
        global $_mw_cache_obj;
        $local_obj = $_mw_cache_obj;
    } else {
        $cache_storage_type = "\mw\cache\\" . $cache_storage_type;
        $local_obj = new $cache_storage_type;

    }
	
	if(!is_object($local_obj)){
		 $local_obj = new \mw\cache\Files();
	}
	
	//d($cache_group);
	
     $local_obj->delete($cache_group);
}

/**
 *  Gets the data from the cache.
 *
 *  If data is not found it return false
 *
 *
 *  @example
 * <code>
 *
 * $cache_id = 'my_cache_'.crc32($sql_query_string);
 * $cache_content = cache_get_content($cache_id, 'my_cache_group');
 *
 * </code>
 *
 *
 *
 *
 * @param string $cache_id id of the cache
 * @param string $cache_group (default is 'global') - this is the subfolder in the cache dir.
 *
 * @param bool $cache_storage_type You can pass custom cache object or leave false.
 * @return  mixed returns array of cached data or false
 * @package Cache
 *
 */
function cache_get_content($cache_id, $cache_group = 'global', $cache_storage_type = false)
{
	static $cache_default;
     global $_mw_cache_obj;
	 
	if ($cache_storage_type == false or $cache_storage_type == 'files') {
        $local_obj = $_mw_cache_obj;
		
    } else {
        $cache_storage_type = "\mw\cache\\" . $cache_storage_type;
        $local_obj = new $cache_storage_type;

    }
	
	if(!is_object($local_obj)){
		 if(!is_object($cache_default)){
			  $local_obj = $cache_default = new \mw\cache\Files();

		 } else {
			$local_obj = $cache_default ;
			 
		}
	}

    return $local_obj->get($cache_id, $cache_group);
}

/**
 * Stores your data in the cache.
 * It can store any value that can be serialized, such as strings, array, etc.
 *
 * @example
 * <code>
 * //store custom data in cache
 * $data = array('something' => 'some_value');
 * $cache_id = 'my_cache_id';
 * $cache_content = cache_save($data, $cache_id, 'my_cache_group');
 * </code>
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
 * @package Cache
  */
function cache_save($data_to_cache, $cache_id, $cache_group = 'global', $cache_storage_type = false)
{

    if ($cache_storage_type == false  or $cache_storage_type == 'files') {
        global $_mw_cache_obj;
        $local_obj = $_mw_cache_obj;
		 
    } else {
        $cache_storage_type = "\mw\cache\\" . $cache_storage_type;
        $local_obj = new $cache_storage_type;

    }
		if(!is_object($local_obj)){
		 $local_obj = new \mw\cache\Files();
	}
	
	
	
    // d($data_to_cache);
    return $local_obj->save($data_to_cache, $cache_id, $cache_group);

}


api_expose('clearcache');
/**
 * Clears all cache data
 * @example
 * <code>
 * //delete all cache 
 *  clearcache();
 * </code>
 * @return boolean
 * @package Cache
  */
function clearcache($cache_storage_type = false)
{
    if ($cache_storage_type == false or trim($cache_storage_type) == ''  or $cache_storage_type == 'files') {
        global $_mw_cache_obj;
        $local_obj = $_mw_cache_obj;
    } else {
        $cache_storage_type = "\mw\cache\\" . $cache_storage_type;
        $local_obj = new $cache_storage_type;

    }
	
		if(!is_object($local_obj)){
		 $local_obj = new \mw\cache\Files();
	}
	

    return $local_obj->purge();
}

/**
 * Prints cache debug information
 *
 * @return array
 * @package Cache
 * @example
 * <code>
 * //get cache items info 
 *  $cached_items = cache_debug();
 * print_r($cached_items);
 * </code>
 */
function cache_debug()
{
    global $_mw_cache_obj;
		if(!is_object($_mw_cache_obj)){
		 $_mw_cache_obj = new \mw\cache\Files();
	}
	
	
	
    return $_mw_cache_obj->debug();
}

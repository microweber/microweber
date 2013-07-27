<?php
namespace Mw;

/**
 * Here you will find how to work with the cache
 * These functions will allow you to save and get data from the MW cache system
 *
 * @package Cache
 * @category Cache
 * @desc  These functions will allow you to save and get data from the MW cache system
 */


if (!isset($_mw_cache_obj) or is_object($_mw_cache_obj) == false) {
    $_mw_cache_obj = new \Mw\Cache\Files();
}


class Cache
{


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
     *  \mw\Cache::delete("content");
     *
     * //delete the cache for the content with id 1
     *  \mw\Cache::delete("content/1");
     *
     * //delete the cache for users
     *  \mw\Cache::delete("users");
     *
     * //delete the cache for your custom table eg. my_table
     * \mw\Cache::delete("my_table");
     * </code>
     *
     */
    public function delete($cache_group = 'global', $cache_storage_type = false)
    {
        if ($cache_storage_type == false  or $cache_storage_type == 'files') {
            global $_mw_cache_obj;
            $local_obj = $_mw_cache_obj;
        } else {
            $cache_storage_type = "\Mw\Cache\\" . $cache_storage_type;
            $local_obj = new $cache_storage_type;

        }

        if (!is_object($local_obj)) {
            $local_obj = new \Mw\Cache\Files();
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
     * @example
     * <code>
     *
     * $cache_id = 'my_cache_'.crc32($sql_query_string);
     * $cache_content = \mw\Cache::get($cache_id, 'my_cache_group');
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
    public function get($cache_id, $cache_group = 'global', $timeout = false, $cache_storage_type = false)
    {
        static $cache_default;
        global $_mw_cache_obj;

        if ($cache_storage_type == false or $cache_storage_type == 'files') {
            $local_obj = $_mw_cache_obj;

        } else {
            if (!is_object($cache_storage_type)) {
                 $local_obj = new $cache_storage_type;
            }

        }

        if (!is_object($local_obj)) {
            if (!is_object($cache_default)) {
                $local_obj = $cache_default = new \Mw\Cache\Files();

            } else {
                $local_obj = $cache_default;

            }
        }

        return $local_obj->get($cache_id, $cache_group,$timeout);
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
     * $cache_content = \mw\Cache::save($data, $cache_id, 'my_cache_group');
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
    public function save($data_to_cache, $cache_id, $cache_group = 'global', $cache_storage_type = false)
    {

        if ($cache_storage_type == false  or $cache_storage_type == 'files') {
            global $_mw_cache_obj;
            $local_obj = $_mw_cache_obj;

        } else {

            if (!is_object($cache_storage_type)) {
                $local_obj = new $cache_storage_type;
            }


        }
        if (!is_object($local_obj)) {
            $local_obj = new \Mw\Cache\Files();
        }


        // d($data_to_cache);
        return $local_obj->save($data_to_cache, $cache_id, $cache_group);

    }

    public function clear()
    {
        return  $this->purge();
    }
    public function flush()
    {
        return  $this->purge();
    }

    /**
     * Clears all cache data
     * @example
     * <code>
     * //delete all cache
     *  mw('cache')->flush();
     * </code>
     * @return boolean
     * @package Cache
     */
    public function purge($cache_storage_type = false)
    {
        if ($cache_storage_type == false or trim($cache_storage_type) == ''  or $cache_storage_type == 'files') {
            global $_mw_cache_obj;
            $local_obj = $_mw_cache_obj;
        } else {
            $cache_storage_type = "\Mw\Cache\\" . $cache_storage_type;
            $local_obj = new $cache_storage_type;

        }

        if (!is_object($local_obj)) {
            $local_obj = new \Mw\Cache\Files();
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
    public function debug()
    {
        global $_mw_cache_obj;
        if (!is_object($_mw_cache_obj)) {
            $_mw_cache_obj = new \Mw\Cache\Files();
        }


        return $_mw_cache_obj->debug();
    }

}
